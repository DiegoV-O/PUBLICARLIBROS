<?php
require_once __DIR__ . '/CrudModel.php';

class autores extends CrudModel
{
    protected static $table = 'autor';
    protected static $columns = ['CI', 'nombre', 'apellidos', 'biografia'];
}
