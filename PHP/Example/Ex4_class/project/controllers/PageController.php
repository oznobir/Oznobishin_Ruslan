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
        $this->data['page'] =  (new PageModel())->getBySlug($this->parameters['p']);
        if (!$this->data['page']) $this->redirect('/menu/');
        $this->data['menu'] =  (new PageModel())->getAllByMenu_id($this->data['page']['menu_id']);
        $this->data['content'] = $this->render('project/views/pageMenuView.php');
        $this->data['content_head'] = '';
        $this->data['content_foot'] = '';
        echo $this->render('project/views/pageLayout.php');

    }
}
