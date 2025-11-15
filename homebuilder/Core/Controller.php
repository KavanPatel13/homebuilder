<?php
namespace Core;

class Controller
{
    protected $routeParams = [];

    public function __construct($routeParams = [])
    {
        $this->routeParams = $routeParams;
        // Start session if not already
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function view($view, $data = [])
    {
        $viewFile = __DIR__ . '/../app/views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            echo "View not found: $viewFile";
            return;
        }
        extract($data);
        require $viewFile;
    }

    protected function loadModel($modelName)
    {
        $modelFile = __DIR__ . '/../app/models/' . $modelName . '.php';
        if (file_exists($modelFile)) {
            require_once $modelFile;
            $class = '\\App\\Models\\' . $modelName;
            if (class_exists($class)) {
                return new $class();
            }
        }
        return null;
    }

    protected function redirect($path)
    {
        $config = require __DIR__ . '/../Config/config.php';
        $base = rtrim($config['BASE_URL'], '/');
        $target = $base . '/index.php?url=' . ltrim($path, '/');
        header('Location: ' . $target);
        exit;
    }

    protected function authorize($roles)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            $this->redirect('auth');
        }
        $allowed = is_array($roles) ? $roles : [$roles];
        if (!in_array($user['role'], $allowed, true)) {
            $this->redirect('auth');
        }
    }
}
