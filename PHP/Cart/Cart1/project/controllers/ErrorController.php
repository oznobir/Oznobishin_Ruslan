<?php
namespace Project\Controllers;

use Core\Controller;

class ErrorController extends Controller
{
    /**
     * @return void
     */
    public function notFound(): void
    {
        $this->data['title'] = 'Not Found';
        $this->data['description'] = 'Not Found';
        echo $this->render('project/views/default/notFound.php');
    }
}