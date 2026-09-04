<?php
require_once __DIR__ . '/../models/autor_libro.php';
class autor_libroController{
    public function getAll()
    {
        $autor_libro=autor_libro::all();
        echo json_encode($autor_libro);
         
    }
}