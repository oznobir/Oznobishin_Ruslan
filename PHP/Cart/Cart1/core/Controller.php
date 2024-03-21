<?php

namespace Core;
/**
 * Base Controller
 */
class Controller
{

    protected array|null $data =[];
    protected array|null $parameters;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }
    public function render($pathView): bool|string|null
    {
        return (new View($this->parameters))->renderView($pathView, $this->data);
    }

    public function redirect($url): void
    {
        header("Location: $url");
    }
}