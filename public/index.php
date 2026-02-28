<?php
// Start Session for Authentication and CSRF
session_start();

// Load Config
require_once '../config/config.php';

require_once '../app/helpers/Session.php';
require_once '../app/helpers/lang_helper.php';
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

// Language Route
$router->addRoute('GET', 'lang/switch/{lang}', 'LanguageController@switch');

// Auth Routes
$router->addRoute('GET', 'auth/login', 'AuthController@login');
$router->addRoute('POST', 'auth/login', 'AuthController@login');
$router->addRoute('GET', 'auth/register', 'AuthController@register');
$router->addRoute('POST', 'auth/register', 'AuthController@register');
$router->addRoute('GET', 'auth/logout', 'AuthController@logout');

// App Routes
$router->addRoute('GET', 'student/dashboard', 'StudentController@dashboard');
$router->addRoute('GET', 'student/friends', 'StudentController@friends');
$router->addRoute('POST', 'student/friends', 'StudentController@friends');
$router->addRoute('GET', 'student/exercise/{worldId}/{stageId}', 'StudentController@exercise');
$router->addRoute('POST', 'student/submit_exercise', 'StudentController@submit_exercise');

// Instructor/Admin Routes
$router->addRoute('GET', 'admin/index', 'AdminController@index');
$router->addRoute('POST', 'admin/reset_progress', 'AdminController@reset_progress');
$router->addRoute('GET', 'instructor/dashboard', 'InstructorController@dashboard');
$router->addRoute('GET', 'admin/stages', 'AdminController@stages');
$router->addRoute('GET', 'admin/edit_stage/{id}', 'AdminController@edit_stage');
$router->addRoute('POST', 'admin/edit_stage/{id}', 'AdminController@edit_stage');
$router->addRoute('GET', 'admin/exercises', 'AdminController@exercises');
$router->addRoute('GET', 'admin/edit_exercise/{id}', 'AdminController@edit_exercise');
$router->addRoute('POST', 'admin/edit_exercise/{id}', 'AdminController@edit_exercise');

// Dispatch
$router->dispatch();
