<?php

namespace core\base\exceptions;

use core\base\controllers\BaseMethods;
use Exception;

class DbException extends Exception
{
    protected mixed $messages;
    use BaseMethods;

    public function __construct($messages = '', $code = 0)
    {
        parent::__construct($messages, $code);
        $this->messages = include 'messages.php';
        $error = $this->getMessage() ?: $this->messages[$this->getCode()];
        $error .= "\r\nfile: {$this->getFile()} \r\nin line: {$this->getLine()}\r\n";
        if ($this->messages[$this->getCode()]) $this->message = $this->messages[$this->getCode()];
        $this->writeLog($error, 'db_log.txt');
    }
}