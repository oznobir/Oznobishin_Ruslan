<?php
namespace Project\Controllers;
use \Core\Controller;
use \Core\View;

class ErrorController extends Controller
{
    public function notFound(): void
    {
        $this->view = new View();
        $dataLayout ['mainMenu'] = $this->view->renderView('project/views/notFound.php', []);
        $this->view = new View();
        $dataLayout ['title'] = 'Not Found';
        $dataLayout ['desc'] = 'Not Found';
        echo $this->view->renderView('project/views/menuAllLayout.php', $dataLayout);
    }
}