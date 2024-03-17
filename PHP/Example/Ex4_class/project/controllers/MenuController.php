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
        $dataMenu = (new MenuModel())->getAll();
        $dataLayout ['content'] = (new View())->renderView('project/views/menuAllView.php', $dataMenu);
        $dataLayout ['title'] = 'Главное меню';
        $dataLayout ['description'] = 'Примеры скриптов РНР. Изучаем вместе';
        echo (new View())->renderLayout('project/views/allLayout.php', $dataLayout);
    }
}
