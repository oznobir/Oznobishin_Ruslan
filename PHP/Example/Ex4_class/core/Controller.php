<?php
/**
 * Controller
 */
namespace Core;

class Controller
{
//    protected object $model;
//    protected object $view;
    protected array|null $data;
    protected array|null $parameters;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }
}