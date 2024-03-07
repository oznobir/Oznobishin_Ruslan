<?php
/*
 * Файл начальных настроек
 */

//************************* THEME ***************************/
const THEME = 'default';                         // Используемая тема всей страницы (theme)
// const THEME = '';
//************************* PATH ***************************/
// $_SERVER['DOCUMENT_ROOT'] и DIRECTORY_SEPARATOR
const CONTROLLERS_PATH = 'controllers/';   // Путь к контроллерам
const VIEWS_PATH = 'views/'.THEME.'/';     // Путь к шаблонам страницы
//************************* FILES *****************************/
const MODELS_PATH = 'models/files/';    // Путь к моделям (FILES-models)
const DATA_PATH = 'data/data_menu.php';        // Файл настроек и подключение к Data
