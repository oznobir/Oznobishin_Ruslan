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
        $dataLayout =  (new PageModel())->getBySlug($this->parameters['p']);
        if (!$dataLayout) {
            header("Location: /menu/");
            die();
        }
        $dataView['menu'] =  (new PageModel())->getAllByMenu_id($dataLayout['menu_id']);
        $dataView['page'] = $this->parameters['p'];
        $dataLayout ['menu'] = (new View())->renderView('project/views/pageMenuView.php', $dataView);
        unset($dataLayout ['id']);
        unset($dataLayout ['menu_id']);
        unset($dataLayout ['slug']);
        $dataLayout ['content_head'] = '';
        $dataLayout ['content_foot'] = '';
        echo (new View())->renderLayout('project/views/pageLayout.php', $dataLayout);
    }
}
