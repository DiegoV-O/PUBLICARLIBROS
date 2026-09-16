<?php
require_once __DIR__ . '/CrudModel.php';

class lector extends CrudModel
{
    protected static $table = 'lector';
    protected static $columns = ['CI', 'nombre', 'apellidos', 'email', 'cod_usuario'];
}