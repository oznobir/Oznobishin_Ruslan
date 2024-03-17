<?php
namespace Core;
class View
{
    /**
     * @param string $layoutPath
     * @param array|null $data
     * @return false|string|void
     */
    public function renderLayout(string $layoutPath, array $data = null)
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
     * @param array|null $data
     * @return false|string|void
     */
    public function renderView(string $viewPath, array $data = null)
    {
        if (file_exists($viewPath)) {
            ob_start();
            extract($data);
            include $viewPath;
            return ob_get_clean();
        } else {
            echo "Не найден файл с View по пути $viewPath (renderView)";
            die();
        }
    }
}