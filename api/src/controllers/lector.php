<?php
require_once __DIR__ . '/../models/lector.php';
class lectorController{
    public function getAll()
    {
        $lector=lector::all();
        echo json_encode($lector);
         
    }
}