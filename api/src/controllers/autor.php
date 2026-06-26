<?php
require_once "../src/Models/autor.php";
class autorController{
    public function getAll()
    {
        $autor=autor::all();
        echo json_encode($autor);
         
    }
}