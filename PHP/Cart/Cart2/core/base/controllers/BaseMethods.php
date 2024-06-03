<?php

namespace core\base\controllers;

use DateTime;
use JetBrains\PhpStorm\NoReturn;

trait BaseMethods
{
    /**
     * @param array|string $str
     * @return array|string
     */
    protected function clearTags($str): array|string
    {
        if (is_array($str)) {
            foreach ($str as $key => $itemStr)
                $str[$key] = trim(strip_tags($itemStr));
            return $str;
        } else return trim(strip_tags($str));
    }

    /**
     * @param string|int|float $num
     * @return float|int
     */
    protected function num($num): float|int
    {
        return (!empty($num) && preg_match('/\d/', $num)) ?
            preg_replace('/[^\d.]/', '', $num) * 1 : 0;
    }

    /**
     * @return bool
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    /**
     * @return bool
     */
    protected function isAjax(): bool
    {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'ajaxrequest');
    }

    /**
     * @return void
     */
    protected function getStyles(): void
    {
        if ($this->styles) {
            foreach ($this->styles as $style)  echo '<link rel="stylesheet" href="' . $style . '">';
        }
    }

    /**
     * @return void
     */
    protected function getScripts(): void
    {
        foreach ($this->scripts as $script)  echo '<script src="' . $script . '"></script>';
    }

    /**
     * @param string|false $url
     * @param int|false $code
     * @return void
     */
    #[NoReturn] protected function redirect($url = false, $code = false): void
    {
        if ($code) {
            $codes = ['301' => 'HTTP/1.1 301 Moved Permanently'];
            if ($codes[$code]) header($codes[$code]);
        }
        if ($url) $redirect = $url;
        else $redirect = $_SERVER['HTTP_REFERER'] ?? PATH;

        header("Location: $redirect");
        exit();
    }

    /**
     * @param string $message
     * @param string $file
     * @param string $event
     * @return void
     */
    protected function writeLog(string $message, string $file = 'log.txt', string $event = 'Fault'): void
    {
        $dataTime = new DateTime();
        $str = "$event: {$dataTime->format('d-m-Y H:i:s')} - $message\r\n";
        file_put_contents("log/$file", $str, FILE_APPEND);
    }
}