<?php
namespace Core;
/**
 * Base View
 */
class View
{
    private array|null $parameters;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
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
            extract($this->parameters);
            if ($data) extract($data);
            include $viewPath;
            return ob_get_clean();
        } else {
            echo "Не найден файл с View по пути $viewPath (renderView)";
            die();
        }
    }
}