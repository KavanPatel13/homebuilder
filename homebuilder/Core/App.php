<?php
namespace Core;

class App
{
    protected $controller = 'AuthController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        if (isset($url[0]) && $url[0] !== '') {
            $controllerName = ucfirst($url[0]) . 'Controller';
            $controllerFile = __DIR__ . '/../app/Controller/' . $controllerName . '.php';
            if (file_exists($controllerFile)) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }

        $controllerClass = '\\App\\Controller\\' . $this->controller;
        if (!class_exists($controllerClass)) {
            $controllerFile = __DIR__ . '/../app/Controller/' . $this->controller . '.php';
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
            }
        }

        if (!class_exists($controllerClass)) {
            die('Controller not found: ' . $controllerClass);
        }

        $this->controller = new $controllerClass();

        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    protected function parseUrl()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return ['auth', 'index'];
    }
}
