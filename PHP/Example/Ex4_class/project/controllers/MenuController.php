<?php

namespace Project\Controllers;

use \Core\View;
use \Core\Controller;
use Project\Models\MenuModel;

/**
 * Controller
 */
class MenuController extends Controller
{

    /**
     * @return void
     */
    public function show(): void
    {
        $this->data = (new MenuModel())->getAll();
        $this->data['content'] = $this->render('project/views/menuAllView.php');
        $this->data['title'] = 'Главное меню';
        $this->data['description'] = 'Примеры скриптов РНР. Изучаем вместе';
        echo $this->render('project/views/allLayout.php');
    }
}
