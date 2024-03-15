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
        $this->model = new MenuModel();
        $dataMenu = $this->model->getAll();
        $this->view = new View();
        $dataLayout ['mainMenu'] = $this->view->renderView('project/views/menuAllView.php', $dataMenu);
        $dataLayout ['title'] = 'Главное меню';
        $dataLayout ['desc'] = 'Примеры скриптов РНР. Изучаем вместе';
        $this->view = new View();
        echo $this->view->renderLayout('project/views/menuAllLayout.php', $dataLayout);
    }
//    public function create() {}
//    public function update() {}
//    public function delete() {}
}
