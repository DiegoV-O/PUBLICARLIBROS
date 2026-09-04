<?php
require_once __DIR__ . '/../models/lectura_libro.php';
class lectura_libroController{
    public function getAll()
    {
        $lectura_libro=lectura_libro::all();
        echo json_encode($lectura_libro);
         
    }
}