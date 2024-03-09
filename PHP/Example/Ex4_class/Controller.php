<?php
/**
 * Controller
 */
class Controller
{
    private object $model;
    private object $view;
    private array $parameters;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return void
     */
    public function showOne(): void
    {
        $this->model = new Model();
        $dataPage = $this->model->getBySlug($this->parameters['slug']);
        if (!$dataPage) {
            header("Location: index.php?p=all");
            die();
        }
        $this->model = new Model();
        $dataMenuPage = $this->model->getAllByMenu_id($dataPage);
        $this->view = new View();
        $this->view->includeView('views/pageMenuView.php');
        $dataLayout ['menu'] = showMenuPage($dataMenuPage, $dataPage['slug']);
        $dataLayout ['desc'] = $dataPage['description'];
        $dataLayout ['title'] = $dataPage['title'];
        $dataLayout ['content1'] = $dataPage['form'];
        $dataLayout ['content2_head'] = '';
        $dataLayout ['content2_tabs'] = $dataPage['content'];
        $dataLayout ['content2_foot'] = '';
        $this->view = new View();
        echo $this->view->renderLayout('views/pageLayout.php', $dataLayout);
    }

    /**
     * @return void
     */
    public function showAll(): void
    {
        $this->model = new Model();
        $dataMenu = $this->model->getAll();
        $this->view = new View();
        $dataLayout ['mainMenu'] = $this->view->renderView('views/menuAllView.php', $dataMenu);
        $dataLayout ['title'] = 'Главное меню';
        $dataLayout ['desc'] = 'Примеры скриптов РНР. Изучаем вместе';
        $this->view = new View();
        echo $this->view->renderLayout('views/menuAllLayout.php', $dataLayout);
    }
//    public function create() {}
//    public function update() {}
//    public function delete() {}
}
