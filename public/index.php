<?php
// Start Session for Authentication and CSRF
session_start();

// Load Config
require_once '../config/config.php';

require_once '../app/helpers/Session.php';
require_once '../app/helpers/url_helper.php';

// Autoload Core Libraries
spl_autoload_register(function ($className) {
    // Map namespaces to file paths if needed, 
    // or simply load core files if they exist
    $corePath = '../app/core/' . $className . '.php';
    if (file_exists($corePath)) {
        require_once $corePath;
    }
});

// Init Router
$router = new Router();

// Default / Guest Route
$router->addRoute('GET', '', 'AuthController@login');

// Auth Routes
$router->addRoute('GET', 'auth/login', 'AuthController@login');
$router->addRoute('POST', 'auth/login', 'AuthController@login');
$router->addRoute('GET', 'auth/register', 'AuthController@register');
$router->addRoute('POST', 'auth/register', 'AuthController@register');
$router->addRoute('GET', 'auth/logout', 'AuthController@logout');

// App Routes
$router->addRoute('GET', 'student/dashboard', 'StudentController@dashboard');
$router->addRoute('GET', 'student/exercise/{worldId}/{stageId}', 'StudentController@exercise'); // adjusted parameters
$router->addRoute('GET', 'instructor/dashboard', 'InstructorController@dashboard');

// Dispatch
$router->dispatch();
