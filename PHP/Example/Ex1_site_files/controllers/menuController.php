<?php

/**
 * @param string $loadPage
 * @return void
 */
function allAction( $loadPage = '')
{
    include_once MODELS_PATH . 'menuModel.php';
    $dataView = getDataMainMenu();
    $mainMenu = loadViewOb('menuAllView.php', $dataView);
    $dataLayout ['mainMenu'] = $mainMenu;
    $dataLayout ['title'] = 'Содержание';
    $dataLayout ['desc'] = 'Главное меню';
    loadView('menuAllLayout.php', $dataLayout);
}

/**
 * @param array $loadPage
 * @return void
 */
function oneAction($loadPage = '')
{
    include_once MODELS_PATH . 'pageModel.php';
    $data = getDataMainMenu();
    $page = $loadPage['parameters']['p'];
    $data_parent = getDataParent($data, $page);
    $data_p = getDataP($data_parent, $page);
    if ($data_p) {
        if (file_exists("pages/{$data_p['dir']}/{$data_p['content2'][0]['path']}")) {
            loadView('pageMenuView.php');
            $dataLayout ['menu'] = showMenuPage($data_parent['children'], $page);
            $dataLayout ['title'] = "Пример $page. {$data_parent['desc']}";
            $dataLayout ['desc'] = $data_p['desc'];
            $dataLayout ['content1'] = loadViewOb('showContent1.php', $data_p);
            loadView('showContent2.php');
            $dataLayout ['content2'] = showContent2($data_p);
            loadView('pageLayout.php', $dataLayout);
        } else {
            $_SESSION ['message'] = [
                'text' => "Файл 'pages/{$data_p['dir']}/{$data_p['content2'][0]['path']}' не найден.",
                'status' => "error"
            ];
            header("Location: index.php?p=all");
            die();
        }
    } else {
        $_SESSION ['message'] = [
            'text' => "Нет данных примера $page в 'data/data_menu.php'.",
            'status' => "error"
        ];
        header("Location: index.php?p=all");
        die();
    }
}