<?php

namespace core;

class Route
{
    private string $path;
    private string $controller;
    private string $action;

    /**
     * @param string $path
     * @param string $controller
     * @param string $action
     */
    public function __construct(string $path, string $controller, string $action)
    {
        $this->path = $path;
        $this->controller = $controller;
        $this->action = $action;
    }

    /**
     * @param $property
     * @return mixed
     */
    public function __get($property)
    {
        return $this->$property;
    }
}