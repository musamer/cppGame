<?php
/*
 * App Router Core Class
 * Maps URLs to controllers and methods
 * Supports GET, POST, DELETE, PUT
 */
class Router
{
    protected $routes = [];

    // Add a route to the routing table
    public function addRoute($method, $url, $action)
    {
        // Replace dynamic parameters like {id} with regex capturing groups
        $route = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_\-]+)', $url);
        // Escape forward slashes
        $route = '#^' . $route . '$#';

        $this->routes[] = [
            'method' => strtoupper($method),
            'url' => $route,
            'action' => $action
        ];
    }

    // Get current clean URL
    public function getUrl()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return $url;
        }
        return '/';
    }

    // Match route and dispatch
    public function dispatch()
    {
        $url = $this->getUrl();
        $method = $_SERVER['REQUEST_METHOD'];

        // Treat root as empty string matching for some setups
        if ($url == '/') $url = '';

        foreach ($this->routes as $route) {
            if ($route['method'] == $method && preg_match($route['url'], $url, $matches)) {
                // Get parameters from URL
                $params = [];
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }

                // Parse Action (e.g., 'HomeController@index')
                $parts = explode('@', $route['action']);
                $controllerName = $parts[0];
                $methodName = $parts[1];

                return $this->executeAction($controllerName, $methodName, $params);
            }
        }

        // If no route match, return 404
        $this->sendNotFound();
    }

    protected function executeAction($controllerName, $methodName, $params)
    {
        $controllerFile = '../app/controllers/' . $controllerName . '.php';

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new $controllerName();

            if (method_exists($controller, $methodName)) {
                // Call method with parameters
                call_user_func_array([$controller, $methodName], $params);
            } else {
                $this->sendNotFound("Method $methodName not found in $controllerName");
            }
        } else {
            $this->sendNotFound("Controller $controllerName not found");
        }
    }

    protected function sendNotFound($msg = "Page not found")
    {
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 Not Found</h1>";
        echo "<p>$msg</p>";
        exit;
    }
}
