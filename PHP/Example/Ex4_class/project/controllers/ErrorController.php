<?php
namespace Project\Controllers;
use \Core\Controller;
use \Core\View;

class ErrorController extends Controller
{
    /**
     * @return void
     */
    public function notFound(): void
    {
        $this->data['content'] = $this->render('project/views/notFound.php');
        $this->data['title'] = 'Not Found';
        $this->data['description'] = 'Not Found';
        echo $this->render('project/views/allLayout.php');
    }
}