<?php
require_once __DIR__ . "/../models/autores.php";

require_once __DIR__ . '/CrudController.php';
require_once __DIR__ . '/../models/autor_libro.php';

class autor_libroController extends CrudController
{
    protected static $model = 'autor_libro';
    protected static $required = ['cod_libro', 'cod_autor'];
    protected static $label = 'relacion autor-libro';
}
