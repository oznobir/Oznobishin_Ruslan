<?php

namespace core\base\controllers;

use JetBrains\PhpStorm\NoReturn;

trait BaseMethods
{
    protected function clearTags($str): array|string
    {
        if (is_array($str)) {
            foreach ($str as $key => $itemStr) $str[$key] = trim(strip_tags($itemStr));
            return $str;
        } else return trim(strip_tags($str));
    }

    protected function num($num): float|int
    {
        return $num * 1;
    }

    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    protected function isFetch()
    {

    }

    #[NoReturn] protected function redirect($url = false, $code = false): void
    {
        if ($code) {
            $codes = ['301' => 'HTTP/1.1 301 Moved Permanently'];
            if ($codes[$code]) header($codes[$code]);
        }
        if ($url) $redirect = $url;
        else $redirect = $_SERVER['HTTP_REFERER'] ?? PATH;

        header("Location: $redirect");
        die();
    }

    protected function writeLog($message, $file = 'log.txt', $event = 'Fault'): void
    {
        $dataTime = new \DateTime();
        $str = "$event: {$dataTime->format('d-m-Y H:i:s')} - $message\r\n";
        file_put_contents("log/$file", $str, FILE_APPEND);
    }
}