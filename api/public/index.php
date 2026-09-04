<?php
if($_SERVER['REQUEST_METHOD']=='OPTIONS')
    {
        exit;
    }
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . "/../src/router.php";
require_once __DIR__ . "/../src/controllers/UserController.php";
require_once __DIR__ . "/../src/controllers/ProductoController.php";
require_once __DIR__ . "/../src/controllers/PublicacionCrud.php";

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
$addCrudRoutes = function ($path, $controller) use ($route) {
    $route->add('GET', $path, $controller . '@getAll');
    $route->add('GET', $path . '/{id}', $controller . '@getOne');
    $route->add('POST', $path, $controller . '@add');
    $route->add('PUT', $path . '/{id}', $controller . '@update');
    $route->add('DELETE', $path . '/{id}', $controller . '@delete');
};
$addCrudRoutes('/usuario', 'usuarioController');
$addCrudRoutes('/autor', 'autorController');
$addCrudRoutes('/lector', 'lectorController');
$addCrudRoutes('/libro', 'libroController');
$addCrudRoutes('/lectura_libro', 'lectura_libroController');
$route->add('GET', '/autor_libro', 'autor_libroController@getAll');
$route->add('GET', '/autor_libro/{id}/{id}', 'autor_libroController@getOne');
$route->add('POST', '/autor_libro', 'autor_libroController@add');
$route->add('PUT', '/autor_libro/{id}/{id}', 'autor_libroController@update');
$route->add('DELETE', '/autor_libro/{id}/{id}', 'autor_libroController@delete');








$route->run();
