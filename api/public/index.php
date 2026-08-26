<?php
if($_SERVER['REQUEST_METHOD']=='OPTIONS')
    {
        exit;
    }
require_once "../src/Router.php";
require_once "../src/Controllers/UserController.php";
require_once "../src/Controllers/ProductoController.php";

use App\Router;
$route=new Router();
//direccion para usuarios 
$route->add('GET','/','UserController@getAll');
$route->add('GET','/users','UserController@getAll');
//direccion de productos
$route->add('GET','/productos','ProductoController@getAll');
$route->add('PUT','/productos/{id}','ProductoController@update');
$route->add('POST','/productos','ProductoController@add');
$route->add('DELETE','/productos/{id}','ProductoController@delete');





$route->run();
