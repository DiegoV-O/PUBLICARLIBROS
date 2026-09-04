<?php
require_once __DIR__ . '/../config/conexionDB.php';
class Productos
{   
    //mostrar producto
    public static function all()
    {
        $sql = "SELECT * FROM productos";
        return ConexionPDO::query($sql); //self::$users;
    }
    //actualizar producto
    public static function update($id,$data)
    {
        if(isset($data['id'])) {
            unset($data['id']);
        }
        $campos=[];
        $valores=[];
        //construir datos
        foreach($data as $columna=>$valor) {
                $campos[]="$columna=:$columna";
                $valores[":$columna"]=$valor;
            }
        $stringCampos=implode(",",$campos);
        //preparamos la consulta
        $sql="UPDATE productos SET $stringCampos WHERE id=:id";
        $valores[':id']=$id;
        $result=ConexionPDO::execute($sql, $valores,false);
        //$sql = "SELECT * FROM productos";
        return $sql; //ConexionPDO:query($sql); 
    }
    //adiccionar producto
    public static function add($data)
    {
        $campos=[];
        $parametros=[];
        $valores=[];
        //construir datos
        foreach($data as $columna=>$valor) {
                $campos[]=$columna;
                $parametros[]=":$columna";
                $valores[":$columna"]=$valor;
            }

        $stringCampos=implode(",",$campos);
        $stringParametros=implode(",",$parametros);
        //preparamos la consulta
        $sql="INSERT INTO productos ($stringCampos) VALUES ($stringParametros)";
        $result=ConexionPDO::execute($sql, $valores,true);
        return $result;
    }
    //eliminar producto
    public static function delete($id)
    {
        $sql="DELETE FROM productos WHERE id=:id";
        $valores=[
            ":id"=>$id
        ];
        $result=ConexionPDO::execute($sql, $valores,false);
        return $result;
    }
    
}