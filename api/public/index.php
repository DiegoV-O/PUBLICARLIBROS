<?php
if($_SERVER['REQUEST_METHOD']=='OPTIONS')
    {
        exit;
    }
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . "/../src/router.php";
require_once __DIR__ . "/../src/controllers/UserController.php";
require_once __DIR__ . "/../src/controllers/autorController.php";
require_once __DIR__ . "/../src/controllers/lectorController.php";
require_once __DIR__ . "/../src/controllers/libroController.php";
require_once __DIR__ . "/../src/controllers/lectura_libroController.php";
require_once __DIR__ . "/../src/controllers/autor_libroController.php";

use App\Router;
$route=new Router();
//direccion para usuarios 
$route->add('GET','/','UserController@getAll');
$route->add('GET','/users','UserController@getAll');        
$route->add('GET','/users/{id}','UserController@getById');
$route->add('PUT','/users/{id}','UserController@update');
$route->add('POST','/users','UserController@add');
$route->add('DELETE','/users/{id}','UserController@delete');
//direccion de productos
// $route->add('GET','/productos','ProductoController@getAll');
// $route->add('PUT','/productos/{id}','ProductoController@update');
// $route->add('POST','/productos','ProductoController@add');
// $route->add('DELETE','/productos/{id}','ProductoController@delete');
// $route->add('GET','/autor','autorController@getAll');
// $addCrudRoutes = function ($path, $controller) use ($route) {
//     $route->add('GET', $path, $controller . '@getAll');
//     $route->add('GET', $path . '/{id}', $controller . '@getOne');
//     $route->add('POST', $path, $controller . '@add');
//     $route->add('PUT', $path . '/{id}', $controller . '@update');
//     $route->add('DELETE', $path . '/{id}', $controller . '@delete');
// };
// $addCrudRoutes('/usuario', 'UserController');
// $addCrudRoutes('/autor', 'autorController');
// $addCrudRoutes('/lector', 'lectorController');
// $addCrudRoutes('/libro', 'libroController');
// $addCrudRoutes('/lectura_libro', 'lectura_libroController');
// Rutas CRUD de autores.
$route->add('GET', '/autor', 'autorController@getAll');
$route->add('GET', '/autor/{id}', 'autorController@getById');
$route->add('POST', '/autor', 'autorController@add');
$route->add('PUT', '/autor/{id}', 'autorController@update');
$route->add('DELETE', '/autor/{id}', 'autorController@delete');
$route->add('GET', '/autores', 'autorController@getAll');
$route->add('GET', '/autores/{id}', 'autorController@getById');
$route->add('POST', '/autores', 'autorController@add');
$route->add('PUT', '/autores/{id}', 'autorController@update');
$route->add('DELETE', '/autores/{id}', 'autorController@delete');

// Rutas CRUD de lectores y libros.
$route->add('GET', '/lector', 'lectorController@getAll');
$route->add('GET', '/lector/{id}', 'lectorController@getById');
$route->add('POST', '/lector', 'lectorController@add');
$route->add('PUT', '/lector/{id}', 'lectorController@update');
$route->add('DELETE', '/lector/{id}', 'lectorController@delete');
$route->add('GET', '/libro', 'libroController@getAll');
$route->add('GET', '/libro/{id}', 'libroController@getById');
$route->add('POST', '/libro', 'libroController@add');
$route->add('PUT', '/libro/{id}', 'libroController@update');
$route->add('DELETE', '/libro/{id}', 'libroController@delete');

// Rutas CRUD de lecturas.
$route->add('GET', '/lectura_libro', 'lectura_libroController@getAll');
$route->add('GET', '/lectura_libro/{id}', 'lectura_libroController@getById');
$route->add('POST', '/lectura_libro', 'lectura_libroController@add');
$route->add('PUT', '/lectura_libro/{id}', 'lectura_libroController@update');
$route->add('DELETE', '/lectura_libro/{id}', 'lectura_libroController@delete');

// autor_libro utiliza dos columnas como clave primaria.
$route->add('GET', '/autor_libro', 'autor_libroController@getAll');
$route->add('GET', '/autor_libro/{id}/{id}', 'autor_libroController@getById');
$route->add('POST', '/autor_libro', 'autor_libroController@add');
$route->add('PUT', '/autor_libro/{id}/{id}', 'autor_libroController@update');
$route->add('DELETE', '/autor_libro/{id}/{id}', 'autor_libroController@delete');








$route->run();
