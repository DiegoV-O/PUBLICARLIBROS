<?php
require_once "../src/Models/libro.php";
class libroController{
    public function getAll()
    {
        $libro=libro::all();
        echo json_encode($libro);
         
    }
}