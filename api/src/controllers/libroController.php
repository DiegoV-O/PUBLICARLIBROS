<?php
require_once __DIR__ . '/CrudController.php';
require_once __DIR__ . '/../models/libro.php';

class libroController extends CrudController
{
    protected static $model = 'libro';
    protected static $required = ['titulo'];
    protected static $label = 'libro';
}