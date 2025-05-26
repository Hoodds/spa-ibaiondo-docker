<?php
class Router {
    private $routes = [];

    public function add($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch() {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        $baseFolder = '/spa-ibaiondo/public';
        if (strpos($uri, $baseFolder) === 0) {
            $uri = substr($uri, strlen($baseFolder));
        }

        $uri = str_replace('/index.php', '', $uri);

        $uri = explode('?', $uri)[0];

        if (empty($uri)) {
            $uri = '/';
        }


        foreach ($this->routes as $route) {
            $pattern = $this->convertRouteToRegex($route['path']);

            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                array_shift($matches);

                $controller = $route['controller'];
                $action = $route['action'];

                $controllerFile = BASE_PATH . '/app/controllers/' . $controller . '.php';

                if (!file_exists($controllerFile)) {
                    header("HTTP/1.0 404 Not Found");
                    include BASE_PATH . '/app/views/errors/404.php';
                    return;
                }

                require_once $controllerFile;

                if (!class_exists($controller)) {
                    header("HTTP/1.0 404 Not Found");
                    include BASE_PATH . '/app/views/errors/404.php';
                    return;
                }

                $controllerInstance = new $controller();

                if (!method_exists($controllerInstance, $action)) {
                    header("HTTP/1.0 404 Not Found");
                    include BASE_PATH . '/app/views/errors/404.php';
                    return;
                }

                call_user_func_array([$controllerInstance, $action], $matches);
                return;
            }
        }

        header("HTTP/1.0 404 Not Found");
        include BASE_PATH . '/app/views/errors/404.php';
    }

    private function convertRouteToRegex($route) {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route);
        return '#^' . $pattern . '$#';
    }
}