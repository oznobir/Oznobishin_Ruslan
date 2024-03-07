<?php
/**
 *  Router
 * @param array $parameters массив с get-параметрами
 * @return array массив с controller, action, и др. parameters
 */
function getRoute($parameters)
{
    if (isset($parameters['controller'])) {
//            $controller = ucfirst($parameters['controller']);
        $controller = $parameters['controller'];
        unset($parameters['controller']);
    } else {
        $controller = 'menu';
    }
    if (isset($parameters['action'])) {
        $action = $parameters['action'];
        unset($parameters['action']);
    } else if (isset($parameters['p']) && $parameters['p'] != 'all') {
        $action = 'one';
    } else {
        $action = 'all';
    }
    return ['controller' => $controller, 'action' => $action, 'parameters' => $parameters];
}

/** Router
 * @param array $route массив с controller, action, и др. parameters
 * @return void
 *
 */
function loadPage(array $route): void
{
    include_once CONTROLLERS_PATH . $route['controller'] . 'Controller.php';
    unset($route['controller']);
    // формируем название функции
    $function = $route['action'] . 'Action';
    unset($route['action']);
    // вызываем функцию
    $function($route);
} //end function loadPage
/**
 * View
 * @param string $view путь к файлу view
 * @param array $data массив с данными для view
 * @return void
 */
function loadView(string $view, array $data = []): void
{
    extract($data);
    include VIEWS_PATH . $view;
} //end function loadView
/**
 *  View
 * @param string $view путь к файлу view
 * @param array $data массив с данными для view
 * @return false|string
 */
function loadViewOb($view, $data = []): false|string
{
    $pathView = VIEWS_PATH . $view;
    ob_start();
    include $pathView;
    return ob_get_clean();
} //end loadViewOb
/**
 * Отладочная функция
 * @param $value
 * @param int $die
 * @return void
 */
function d($value, int $die = 1): void
{
    echo 'Debug: <br><pre>';
    print_r($value);
    echo '</pre><br><br>';
    if ($die) die;
}