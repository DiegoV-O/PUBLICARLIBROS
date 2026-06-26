<?php
require_once "../src/Models/lector.php";
class lectorController{
    public function getAll()
    {
        $lector=lector::all();
        echo json_encode($lector);
         
    }
}