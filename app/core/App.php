<?php
/*
 * App Core Class
 * Initializes the Router and dispatching mechanism
 */
class App
{
    protected $router;

    public function __construct()
    {
        $this->router = new Router();

        // Load Routes
        $this->loadRoutes();
    }

    private function loadRoutes()
    {
        // Define default or dummy routes here to test
        $this->router->addRoute('GET', '', 'HomeController@index');
    }

    public function run()
    {
        $this->router->dispatch();
    }
}
