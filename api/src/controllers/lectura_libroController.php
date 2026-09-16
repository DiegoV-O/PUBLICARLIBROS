<?php
require_once __DIR__ . '/CrudController.php';
require_once __DIR__ . '/../models/lectura_libro.php';

class lectura_libroController extends CrudController
{
    protected static $model = 'lectura_libro';
    protected static $required = ['cod_lector', 'cod_libro'];
    protected static $label = 'lectura';
}