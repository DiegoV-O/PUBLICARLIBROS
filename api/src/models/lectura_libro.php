<?php
include_once __DIR__ . "/../Config/conexionDB.php";
class lectura_libro
{
    public static function all()
    {
        $sql = "SELECT * FROM lectura_libro";
        return ConexionPDO::query($sql); //self::$users;
    }
}