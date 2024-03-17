<?php
namespace Project\Controllers;
use \Core\Controller;
use \Core\View;

class ErrorController extends Controller
{
    public function notFound(): void
    {
        $view = new View();
        $dataLayout ['content'] = $view->renderView('project/views/notFound.php', []);
        $layout = new View();
        $dataLayout ['title'] = 'Not Found';
        $dataLayout ['description'] = 'Not Found';
        echo $layout->renderView('project/views/allLayout.php', $dataLayout);
    }
}