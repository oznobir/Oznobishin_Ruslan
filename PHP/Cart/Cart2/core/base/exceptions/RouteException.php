<?php

namespace core\base\exceptions;

use core\base\controllers\BaseMethods;
use Exception;

class RouteException extends Exception
{
    protected mixed $messages;

    use BaseMethods;

    public function __construct($messages = '', $code = 0)
    {
        parent::__construct($messages, $code);
        $this->messages = include 'messages.php';
        $error = $this->getMessage() ?: $this->messages[$this->getCode()];
        $error .= PHP_EOL . "file: {$this->getFile()}" . PHP_EOL . "in line: {$this->getLine()}" . PHP_EOL;
        if ($this->messages[$this->getCode()]) $this->message = $this->messages[$this->getCode()];
        $this->writeLog($error);
    }
}