<?php
require_once __DIR__ . '/CrudModel.php';

class lectura_libro extends CrudModel
{
    protected static $table = 'lectura_libro';
    protected static $columns = ['cod_lector', 'cod_libro', 'fecha_inicio'];
}