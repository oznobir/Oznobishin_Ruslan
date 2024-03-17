<?php

namespace Project\Controllers;

use \Core\View;
use \Core\Controller;
use Project\Models\PageModel;

/**
 * Controller
 */
class PageController extends Controller
{
    /**
     * @return void
     */
    public function show(): void
    {
        $dataView =  (new PageModel())->getBySlug($this->parameters['p']);
        if (!$dataView) {
            header("Location: /menu/");
            die();
        }
        $dataView['menu'] =  (new PageModel())->getAllByMenu_id($dataView['menu_id']);
        unset($dataView ['id']);
        unset($dataView ['menu_id']);
        $dataLayout['content'] = (new View())->renderView('project/views/pageMenuView.php', $dataView);
        $dataLayout['title'] = $dataView['title'];
        $dataLayout['description'] = $dataView['description'];
        $dataLayout ['content_head'] = '';
        $dataLayout ['content_foot'] = '';
        echo (new View())->renderLayout('project/views/pageLayout.php', $dataLayout);
    }
}
