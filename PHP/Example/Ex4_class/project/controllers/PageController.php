<?php

namespace Project\Controllers;
use \Core\Model;
use \Core\View;
use \Core\Controller;

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
        $this->model = new Model();
        $dataPage = $this->model->getBySlug($this->parameters['p']);
        if (!$dataPage) {
            header("Location: index.php?controller=menu");
            die();
        }
        $this->model = new Model();
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
