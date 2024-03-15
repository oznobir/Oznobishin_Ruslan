<?php
namespace Core;
class Router
{
    /**
     * @param array $routes массив с Route
     * @return array массив с controller, action, parameters
     */
    public function getRoute(array $routes): array
    {
        $uri = $_SERVER['REQUEST_URI'];
        foreach ($routes as $route) {
            $pattern = $this->createPattern($route->path);

            if (preg_match($pattern, $uri, $params)) {
                $params = $this->clearParams($params);
                $controller = $route->controller;
                $action = $route->action;
                return ['controller' => 'Project\Controllers\\'.ucfirst($controller).'Controller', 'action' => $action, 'parameters' => $params];
            }
        }

        return ['controller' => 'Project\Controllers\ErrorController', 'action' =>'NotFound', 'parameters' => []];
    }

    /** Создает паттерн из path, указанного в route
     * @param $path
     * @return string
     */
    private function createPattern($path): string
    {
        return '#^' . preg_replace('#/:([^/]+)#', '/(?<$1>[^/]+)', $path) . '/?$#';
    }

    /** Удаляет из массива $params числовые ключи
     * @param array $params
     * @return array
     */
    private function clearParams(array $params): array
    {
        $result = [];
        foreach ($params as $key => $param) {
            if (!is_int($key)) {
                $result[$key] = $param;
            }
        }
        return $result;
    }
}