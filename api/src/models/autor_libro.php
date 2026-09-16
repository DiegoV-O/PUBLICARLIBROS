<?php
require_once __DIR__ . '/CrudModel.php';

class autor_libro extends CrudModel
{
    protected static $table = 'autor_libro';
    protected static $columns = ['cod_libro', 'cod_autor', 'tipo_participacion'];
    protected static $primaryKeys = ['cod_libro', 'cod_autor'];
}