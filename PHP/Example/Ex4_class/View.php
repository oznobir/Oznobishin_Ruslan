<?php

class View
{
    /**
     * @param string $layoutPath
     * @param array|string $data
     * @return false|string|void
     */
    public function renderLayout(string $layoutPath, array|string $data = '')
    {
        if (file_exists($layoutPath)) {
            ob_start();
            extract($data);
            include $layoutPath;
            return ob_get_clean();
        } else {
            echo "Не найден файл с layout по пути $layoutPath";
            die();
        }
    }

    /**
     * @param string $viewPath
     * @param array|string $data
     * @return false|string|void
     */
    public function renderView(string $viewPath, array|string $data = '')
    {
        if (file_exists($viewPath)) {
            ob_start();
            extract($data);
            include $viewPath;
            return ob_get_clean();
        } else {
            echo "Не найден файл с View по пути $viewPath";
            die();
        }
    }

    /**
     * @param string $viewPath
     * @param array|string $data
     * @return void
     */
    public function includeView(string $viewPath, array|string $data = ''): void
    {
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            echo "Не найден файл с View по пути $viewPath";
            die();
        }
    }
}

