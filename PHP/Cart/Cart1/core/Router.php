<?php
namespace Core;
/**
 * Base Router
 */
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
            $pattern = $this->createPattern($route[0]);

            if (preg_match($pattern, $uri, $params)) {
                $params = $this->clearParams($params);
                $controller = $route[1];
                $action = $route[2];

                return ['controller' => 'Project\Controllers\\'.ucfirst($controller).'Controller',
                    'action' => $action, 'parameters' => $params];
            }
        }

        return ['controller' => 'Project\Controllers\ErrorController', 'action' =>'notFound', 'parameters' => []];
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
                $result[$key] = htmlspecialchars($param, ENT_QUOTES, 'UTF-8');
            }
        }
        return $result;
    }
}