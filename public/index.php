<?php 
require_once __DIR__ . '/../includes/app.php';

use Controllers\ActividadesController;
use Controllers\AsistenciasController;
use MVC\Router;
use Controllers\AppController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class,'index']);

$router->get('/actividades', [ActividadesController::class, 'renderizarPagina']);
$router->post('/actividades/guardarAPI', [ActividadesController::class, 'guardarAPI']);
$router->get('/actividades/buscarAPI', [ActividadesController::class, 'buscarAPI']);
$router->post('/actividades/modificarAPI', [ActividadesController::class, 'modificarAPI']);
$router->post('/actividades/eliminarAPI', [ActividadesController::class, 'eliminarAPI']); 

$router->get('/asistencias', [AsistenciasController::class, 'renderizarPagina']);
$router->post('/asistencias/guardarAPI', [AsistenciasController::class, 'guardarAPI']);
$router->get('/asistencias/buscarAPI', [AsistenciasController::class, 'buscarAPI']);
$router->post('/asistencias/eliminarAPI', [AsistenciasController::class, 'eliminarAPI']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();