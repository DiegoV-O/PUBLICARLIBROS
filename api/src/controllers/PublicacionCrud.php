?<?php
require_once __DIR__ . '/../config/conexionDB.php';

abstract class CrudModel {
    protected static $table;
    protected static $fields = [];
    protected static $keys = ['id'];
    protected static $select = [];
    public static function all(): array { return ConexionPDO::query('SELECT ' . static::columns() . ' FROM `' . static::$table . '`'); }
    public static function one(array $id): array {
        $where = static::where($id, $params);
        return $where === null ? [] : ConexionPDO::query('SELECT ' . static::columns() . ' FROM `' . static::$table . '` WHERE ' . $where . ' LIMIT 1', $params);
    }
    public static function create(array $data) {
        $data = static::filter($data, false); if (!$data) return false;
        $cols = array_keys($data); $params = [];
        foreach ($data as $key => $value) $params[":$key"] = $value;
        $sql = 'INSERT INTO `' . static::$table . '` (`' . implode('`, `', $cols) . '`) VALUES (:' . implode(', :', $cols) . ')';
        return ConexionPDO::execute($sql, $params, static::$keys === ['id']);
    }
    public static function update(array $id, array $data) {
        $data = static::filter($data, true); if (!$data) return false;
        $set = []; $params = [];
        foreach ($data as $key => $value) { $set[] = "`$key` = :set_$key"; $params[":set_$key"] = $value; }
        $where = static::where($id, $keyParams); if ($where === null) return false;
        return ConexionPDO::execute('UPDATE `' . static::$table . '` SET ' . implode(', ', $set) . ' WHERE ' . $where, $params + $keyParams, false);
    }
    public static function remove(array $id) {
        $where = static::where($id, $params); return $where === null ? false : ConexionPDO::execute('DELETE FROM `' . static::$table . '` WHERE ' . $where, $params, false);
    }
    private static function filter(array $data, bool $updating): array {
        $allowed = $updating ? array_diff(static::$fields, static::$keys) : static::$fields;
        return array_intersect_key($data, array_flip($allowed));
    }
    private static function where(array $id, &$params) {
        $where = []; $params = [];
        foreach (static::$keys as $key) { if (!array_key_exists($key, $id)) return null; $where[] = "`$key` = :key_$key"; $params[":key_$key"] = $id[$key]; }
        return implode(' AND ', $where);
    }
    private static function columns(): string {
        $fields = static::$select ?: array_merge(static::$keys, static::$fields);
        return '`' . implode('`, `', array_unique($fields)) . '`';
    }
}
class Usuario extends CrudModel { protected static $table='usuario'; protected static $fields=['username','password','rol','activo','fecha_registro']; protected static $select=['id','username','rol','activo','fecha_registro']; }
class autor extends CrudModel { protected static $table='autor'; protected static $fields=['CI','nombre','apellidos','biografia']; }
class lector extends CrudModel { protected static $table='lector'; protected static $fields=['CI','nombre','apellidos','email','cod_usuario']; }
class libro extends CrudModel { protected static $table='libro'; protected static $fields=['isbn','titulo','sinopsis','estado_publicacion','fecha_publicacion']; }
class autor_libro extends CrudModel { protected static $table='autor_libro'; protected static $fields=['cod_libro','cod_autor','tipo_participacion']; protected static $keys=['cod_libro','cod_autor']; }
class lectura_libro extends CrudModel { protected static $table='lectura_libro'; protected static $fields=['cod_lector','cod_libro','fecha_inicio']; }

abstract class CrudController {
    protected $model; protected $required=[]; protected $keys=['id'];
    public function getAll() { $this->out(200, $this->model::all()); }
    public function getOne(...$values) { $id=$this->id($values); if ($id===null) return $this->out(400,['error'=>'Identificador invalido']); $row=$this->model::one($id); $this->out($row?200:404, $row?$row[0]:['error'=>'Registro no encontrado']); }
    public function add() {
        $data=$this->input(); if ($data===null) return; $data=$this->transform($data); $missing=$this->missing($data);
        if ($missing) return $this->out(422,['error'=>'Campos obligatorios faltantes','campos'=>$missing]);
        $result=$this->model::create($data); $this->out($result===false?422:201, $result===false?['error'=>'No se enviaron campos validos']:['estado'=>true,'id'=>$result,'mensaje'=>'Registro creado correctamente']);
    }
    public function update(...$values) {
        $id=$this->id($values); if ($id===null) return $this->out(400,['error'=>'Identificador invalido']); if (!$this->model::one($id)) return $this->out(404,['error'=>'Registro no encontrado']);
        $data=$this->input(); if ($data===null) return; $result=$this->model::update($id,$this->transform($data)); $this->out($result===false?422:200,$result===false?['error'=>'No se enviaron campos validos para actualizar']:['estado'=>true,'mensaje'=>'Registro actualizado correctamente']);
    }
    public function delete(...$values) { $id=$this->id($values); if ($id===null) return $this->out(400,['error'=>'Identificador invalido']); if (!$this->model::one($id)) return $this->out(404,['error'=>'Registro no encontrado']); $this->model::remove($id); $this->out(200,['estado'=>true,'mensaje'=>'Registro eliminado correctamente']); }
    protected function transform(array $data): array { return $data; }
    private function input() { $data=json_decode(file_get_contents('php://input'),true); if (json_last_error()!==JSON_ERROR_NONE || !is_array($data)) { $this->out(400,['error'=>'JSON invalido','detalle'=>json_last_error_msg()]); return null; } return $data; }
    private function missing(array $data): array { return array_values(array_filter($this->required,function($key) use($data) { return !array_key_exists($key,$data) || $data[$key]==='' || $data[$key]===null; })); }
    private function id(array $values) { return count($values)===count($this->keys) ? array_combine($this->keys,$values) : null; }
    protected function out(int $status,array $data) { http_response_code($status); echo json_encode($data,JSON_UNESCAPED_UNICODE); }
}
class usuarioController extends CrudController { protected $model=Usuario::class; protected $required=['username','password']; protected function transform(array $data): array { if (isset($data['password']) && $data['password']!=='') $data['password']=password_hash($data['password'],PASSWORD_DEFAULT); return $data; } }
class autorController extends CrudController { protected $model=autor::class; protected $required=['CI','nombre','apellidos']; }
class lectorController extends CrudController { protected $model=lector::class; protected $required=['CI','nombre','apellidos','email']; }
class libroController extends CrudController { protected $model=libro::class; protected $required=['titulo']; }
class autor_libroController extends CrudController { protected $model=autor_libro::class; protected $required=['cod_libro','cod_autor']; protected $keys=['cod_libro','cod_autor']; }
class lectura_libroController extends CrudController { protected $model=lectura_libro::class; protected $required=['cod_lector','cod_libro']; }
