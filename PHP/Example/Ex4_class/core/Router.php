<?php
namespace Core;
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
        if (isset($parameters['controller'])
            && is_readable('project/controllers/'. ucfirst($parameters['controller']) . 'Controller.php')) {
            $controller = ucfirst($parameters['controller'] . 'Controller');
            unset($parameters['controller']);
        } else {
            $controller = 'PageController';
        }
        if (isset($parameters['action'])) {
            $action = $parameters['action'];
            unset($parameters['action']);
        } else {
            $action = 'show';
        }
        return ['controller' => 'Project\Controllers\\'.$controller, 'action' => $action, 'parameters' => $parameters];
    }
}
