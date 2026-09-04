<?php
require_once __DIR__ . '/../config/conexionDB.php';
class lectura_libro
{
    public static function all()
    {
        $sql = "SELECT * FROM lectura_libro";
        return ConexionPDO::query($sql); //self::$users;
    }
}