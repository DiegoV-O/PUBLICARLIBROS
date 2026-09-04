<?php
require_once __DIR__ . '/../config/conexionDB.php';
class autor
{
    public static function all()
    {
        $sql = "SELECT * FROM autor";
        return ConexionPDO::query($sql); //self::$users;
    }
}