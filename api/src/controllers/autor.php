<?php
require_once __DIR__ . '/../models/autor.php';
class autorController{
    public function getAll()
    {
        $autor=autor::all();
        echo json_encode($autor);
         
    }
}