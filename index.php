<?php
// Front Controller

$requestUri = $_SERVER['REQUEST_URI'];

$routes = [
    '/home' => 'homeController',      
];

$controller = $routes[$requestUri] ?? 'homeController';
require_once 'controllers/' . $controller . '.php';

$action = 'index';
if (isset($_GET['action'])) {
    $_POST = json_decode(file_get_contents('php://input'), true);
    $action = $_GET['action'];
}

call_user_func($action);


