<?php
require_once __DIR__ . '/../models/libro.php';
class libroController{
    public function getAll()
    {
        $libro=libro::all();
        echo json_encode($libro);
         
    }
}