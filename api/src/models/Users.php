<?php
require_once __DIR__ . '/../config/conexionDB.php';
class Users
{
    public static function all()
    {
        $sql = "SELECT * FROM usuario";
        return ConexionPDO::query($sql); //self::$users;
    }
}