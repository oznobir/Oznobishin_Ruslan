<?php

/**
 * @param array $loadPage
 * @return void
 */
function allAction(array $loadPage = []): void
{
    include_once MODELS_PATH . 'menuModel.php';
    $dataView = getDataMainMenu();
    $mainMenu = loadViewOb('menuAllView.php', $dataView);

    $dataLayout ['mainMenu'] = $mainMenu;
    $dataLayout ['title'] = 'title menuAllLayout';
    $dataLayout ['desc'] = 'title menuAllLayout';
    loadView('menuAllLayout.php', $dataLayout);
}
function oneAction($loadPage = ''): void
{
    include_once MODELS_PATH . 'pageModel.php';
    $dataView = getDataPage($loadPage);
    $page = $dataView['slug'];
    loadView('pageFunctionsView.php');
    $pageMenu = getPageMenu($dataView);
    $dataLayout ['menu'] = showMenuPage($pageMenu, $page);
    $dataLayout ['title'] = $dataView['title'];
    $dataLayout ['desc'] = $dataView['description'];
    $dataLayout ['content1'] =  $dataView['form'];
    $dataLayout ['content2_head'] =  '';
    $dataLayout ['content2_foot'] =  '';
    $dataLayout ['content2_tabs'] = $dataView['content'];
    loadView('pageLayout.php', $dataLayout);
}