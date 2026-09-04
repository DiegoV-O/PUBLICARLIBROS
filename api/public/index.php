<?php
if($_SERVER['REQUEST_METHOD']=='OPTIONS')
    {
        exit;
    }
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . "/../src/router.php";
require_once __DIR__ . "/../src/controllers/UserController.php";
require_once __DIR__ . "/../src/controllers/ProductoController.php";
require_once __DIR__ . "/../src/controllers/autor.php";
require_once __DIR__ . "/../src/controllers/autor_libro.php";
require_once __DIR__ . "/../src/controllers/lector.php";
require_once __DIR__ . "/../src/controllers/lectura_libro.php";
require_once __DIR__ . "/../src/controllers/libro.php";

use App\Router;
$route=new Router();
//direccion para usuarios 
$route->add('GET','/','UserController@getAll');
$route->add('GET','/users','UserController@getAll');
$route->add('POST','/users','UsersController@add');
$route->add('PUT','/users/{id}','UsersController@update');
$route->add('DELETE','/users/{id}','UsersController@delete');
//direccion de productos
$route->add('GET','/productos','ProductoController@getAll');
$route->add('PUT','/productos/{id}','ProductoController@update');
$route->add('POST','/productos','ProductoController@add');
$route->add('DELETE','/productos/{id}','ProductoController@delete');
$route->add('GET','/autor','autorController@getAll');
$route->add('GET','/autor_libro','autor_libroController@getAll');
$route->add('GET','/lector','lectorController@getAll');
$route->add('GET','/lectura_libro','lectura_libroController@getAll');
$route->add('GET','/libro','libroController@getAll');






$route->run();
