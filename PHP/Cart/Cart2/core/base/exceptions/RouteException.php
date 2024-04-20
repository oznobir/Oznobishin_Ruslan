<?php

namespace core\base\exceptions;


use core\base\controllers\BaseMethods;

class RouteException extends \Exception
{
    protected mixed $messages;
    use BaseMethods;

    public function __construct($messages = '', $code = 0)
    {
        parent::__construct();
        $this->messages = include 'messages.php';
        $error = $this->getMessage() ? $this->getMessage() : $this->messages[$this->getCode()];
        $error .= "\r\n file: {$this->getFile()} \r\n in line: {$this->getLine()}\r\n";
        if ($this->messages[$this->getCode()]) $this->message = $this->messages[$this->getCode()];
        $this->writeLog($error);
    }
}