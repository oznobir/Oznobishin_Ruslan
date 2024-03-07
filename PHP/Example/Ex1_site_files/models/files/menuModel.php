<?php
/**
 * Модель для главного меню files
 */
function getDataMainMenu()
{
    if (file_exists(DATA_PATH)) {
      return include DATA_PATH;
    } else {
//        $_SESSION ['message'] = [
//            'text' => "Файл 'data_menu.php' не найден.",
//            'status' => "error"
//        ];
//        header("HTTP/1.0 404 Not Found");
        return null;
    }
}