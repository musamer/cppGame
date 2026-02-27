<?php
/*
 * Base Controller
 * Loads the models and views
 */
class Controller
{
    // Load model
    public function model($model)
    {
        // Require model file
        $modelPath = '../app/models/' . $model . '.php';
        if (file_exists($modelPath)) {
            require_once $modelPath;
            // Instantiate model
            return new $model();
        } else {
            die("Model does not exist: $model");
        }
    }

    // Load view
    public function view($view, $data = [])
    {
        // Extract data for easy access in view (makes $data['title'] accessible as $title)
        extract($data);

        // Ensure data is XSS-safe (optional sanitization layer here if desired, OR explicitly when rendering)

        // Check for view file
        $viewPath = '../app/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View does not exist: $view");
        }
    }
}
