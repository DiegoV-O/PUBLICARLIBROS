<?php
require_once __DIR__ . '/CrudController.php';
require_once __DIR__ . '/../models/lector.php';

class lectorController extends CrudController
{
    protected static $model = 'lector';
    protected static $required = ['CI', 'nombre', 'apellidos', 'email'];
    protected static $label = 'lector';
}