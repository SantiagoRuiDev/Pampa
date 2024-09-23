<?php
// PAMPA FRAMEWORK | BETA 1.0 - 2024

require 'vendor/autoload.php';

use Router\Router; // Import router class;

// INSTANCE YOUR APPLICATION
$initRoutes = new Router();

// DEFAULT ROUTE = MAIN ENDPOINT (http://localhost/pampa/api/)
$initRoutes->setDefaultRoute('TestController', 'getExample', '');

                // ENDPOINT  //ACTION   //CONTROLLER    //METHOD  // MIDDLEWARE
                
// $initRoutes->addRoute('test/:ID', 'GET', 'TestController', 'getExample', 'TestMiddleware');
$initRoutes->addRoute('test', 'GET', 'TestController', 'getExample', 'TestMiddleware');
$initRoutes->addRoute('test', 'POST', 'TestController', 'postExample', '');
$initRoutes->addRoute('test', 'DELETE', 'TestController', 'deleteExample', '');
$initRoutes->addRoute('test/:ID', 'PUT', 'TestController', 'putExample', '');
$initRoutes->addRoute('session','GET','TestController','getSessionInfo', '');
$initRoutes->addRoute('session','DELETE','TestController','destroyMySession', '');

// Serve added routes
$initRoutes->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);
