<?php
require_once __DIR__ . '/../config/conexionDB.php';
class libro
{
    public static function all()
    {
        $sql = "SELECT * FROM libro";
        return ConexionPDO::query($sql); //self::$users;
    }
}