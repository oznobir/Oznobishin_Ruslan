<?php

class Router
{
    /** Router
     * @return array массив с controller, action, parameters
     */
    public function getRoute(): array
    {
        foreach ($_GET as $param => $value) {
            $parameters[$param] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
        if (isset($parameters['controller']) && is_readable(ucfirst($parameters['controller']) . 'Controller.php')) {
            $controller = ucfirst($parameters['controller'] . 'Controller');
            unset($parameters['controller']);
        } else {
            $controller = 'Controller';
        }
        if (isset($parameters['action'])) {
            $action = $parameters['action'];
            unset($parameters['action']);
        } else if (isset($parameters['p']) && $parameters['p'] != 'all') {
            $parameters['slug'] = $parameters['p'];
            unset($parameters['p']);
            $action = 'showOne';
        } else {
            $action = 'showAll';
        }
        return ['controller' => $controller, 'action' => $action, 'parameters' => $parameters];
    }
}
