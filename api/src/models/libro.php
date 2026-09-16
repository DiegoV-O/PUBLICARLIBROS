<?php
require_once __DIR__ . '/CrudModel.php';

class libro extends CrudModel
{
    protected static $table = 'libro';
    protected static $columns = ['isbn', 'titulo', 'sinopsis', 'estado_publicacion', 'fecha_publicacion', 'cod_usuario_editor'];
}