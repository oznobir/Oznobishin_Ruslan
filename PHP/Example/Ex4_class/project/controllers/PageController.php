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
        $this->model = new PageModel();
        $dataPage = $this->model->getBySlug($this->parameters['p']);
        if (!$dataPage) {
            header("Location: /menu/");
            die();
        }
        $this->model = new PageModel();
        $dataMenuPage = $this->model->getAllByMenu_id($dataPage);
        $this->view = new View();
        $this->view->includeView('project/views/pageMenuView.php');
        $dataLayout ['menu'] = showMenuPage($dataMenuPage, $dataPage['slug']);
        $dataLayout ['desc'] = $dataPage['description'];
        $dataLayout ['title'] = $dataPage['title'];
        $dataLayout ['content1'] = $dataPage['form'];
        $dataLayout ['content2_head'] = '';
        $dataLayout ['content2_tabs'] = $dataPage['content'];
        $dataLayout ['content2_foot'] = '';
        $this->view = new View();
        echo $this->view->renderLayout('project/views/pageLayout.php', $dataLayout);
    }
}
