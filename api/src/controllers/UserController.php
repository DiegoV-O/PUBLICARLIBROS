<?php
require_once __DIR__ . '/../models/Users.php';
class UserController{
    public function getAll()
    {
        $user=Users::all();
        echo json_encode($user);
         
    }
}




	
