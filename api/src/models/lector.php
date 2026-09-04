<?php
require_once __DIR__ . '/../config/conexionDB.php';
class lector
{
    public static function all()
    {
        $sql = "SELECT * FROM lector";
        return ConexionPDO::query($sql); //self::$users;
    }
}