<?php
include_once __DIR__ . "/../config/conexionDB.php";
class Users
{
    private static $columnasPermitidas = [
        'username',
        'email',
        'password',
        'rol',
        'activo',
    ]; 

    public static function all()
    {
        return ConexionPDO::query("SELECT id, username, email, rol, activo, fecha_registro FROM usuarios ORDER BY id DESC");
    }

    public static function find($id)
    {
        $sql = "SELECT id, username, email, rol, activo, fecha_registro FROM usuarios WHERE id=:id";
        $result = ConexionPDO::query($sql, [':id' => (int) $id]);
        return count($result) > 0 ? $result[0] : null;
    }

    public static function update($id, $data)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }

        $data = self::prepararDatos($data);

        if (count($data) === 0) {
            return 0;
        }

        $campos = [];
        $valores = [];
        foreach ($data as $columna => $valor) {
            $campos[] = ($columna === 'password' ? '`password`' : $columna) . "=:$columna";
            $valores[":$columna"] = $valor;
        }

        $sql = "UPDATE usuarios SET " . implode(',', $campos) . " WHERE id=:id";
        $valores[':id'] = (int) $id;
        return ConexionPDO::execute($sql, $valores);
    }

    public static function add($data)
    {
        $data = self::prepararDatos($data);
        if (count($data) === 0) {
            return 0;
        }

        $campos = [];
        $placeholders = [];
        $valores = [];
        foreach ($data as $columna => $valor) {
            $campos[] = $columna === 'password' ? '`password`' : $columna;
            $placeholders[] = ":$columna";
            $valores[":$columna"] = $valor;
        }

        $sql = "INSERT INTO usuarios (" . implode(',', $campos) . ") VALUES (" . implode(',', $placeholders) . ")";
        return ConexionPDO::execute($sql, $valores, true);
    }

    public static function delete($id)
    {
        $sql = "DELETE FROM usuarios WHERE id=:id";
        $valores = [":id" => $id];
        return ConexionPDO::execute($sql, $valores);
    }

    private static function prepararDatos($data)
    {
        $datos = [];
        foreach ($data as $columna => $valor) {
            if (in_array($columna, self::$columnasPermitidas, true)) {
                $datos[$columna] = is_string($valor) ? trim($valor) : $valor;
            }
        }
        return $datos;
    }
}