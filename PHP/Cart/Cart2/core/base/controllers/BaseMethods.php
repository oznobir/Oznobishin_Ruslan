<?php

namespace core\base\controllers;

use DateTime;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use ReflectionClass;

trait BaseMethods
{
    /**
     * @param array|string $str
     * @param bool $full флаг: true(default) - htmlspecialchars, false - strip_tags
     * @return array|string
     */
    protected function clearTags($str, $full = true): array|string
    {
        if (is_array($str)) {
            foreach ($str as $key => $itemStr)
                $str[$key] = $this->clearTags($itemStr, $full);
            return $str;
        } else {
            if ($full) return trim(htmlspecialchars($str, ENT_QUOTES, 'UTF-8'));
            else  return trim(strip_tags($str));
        }
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

    protected function getController()
    {
        return preg_split('/_?controller/',
            strtolower(preg_replace('/([^A-Z])([A_Z])/', '$1_$2', (new ReflectionClass($this))->getShortName())),
            0, PREG_SPLIT_NO_EMPTY)[0];
    }

    /**
     * @return void
     */
    protected function getStyles(): void
    {
        if ($this->styles) {
            foreach ($this->styles as $style) echo '<link rel="stylesheet" href="' . $style . '">';
        }
    }

    /**
     * @return void
     */
    protected function getScripts(): void
    {
        foreach ($this->scripts as $script) echo '<script type="text/javascript" src="' . $script . '"></script>';
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

    /**
     * @throws Exception
     */
    protected function dateFormat($date)
    {
        if (!$date) return $date;
        $daysArr = [
            'Sunday' => 'Воскресенье', 'Monday' => 'Понедельник', 'Tuesday' => 'Вторник', 'Wednesday' => 'Среда',
            'Thursday' => 'Четверг', 'Friday' => 'Пятница', 'Saturday' => 'Суббота',
        ];
        $monthsArr = [
            1 => 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь',
            'Октябрь', 'Ноябрь', 'Декабрь',
        ];
        $dateArr = [];
        $dateData = new DateTime($date);
        $dateArr['year'] = $dateData->format('Y');
        $dateArr['month'] = $monthsArr[$this->num($dateData->format('m'))];
        $dateArr['monthFormat'] = preg_match('/т$/u', $dateArr['month']) ? $dateArr['month'] . 'а' :
            preg_replace('/[ьй]/u', 'я', $dateArr['month']);
        $dateArr['weekDay'] = $daysArr[$dateData->format('l')];
        $dateArr['day'] = $dateData->format('d');
        $dateArr['time'] = $dateData->format('H:i:s');
        $dateArr['format'] = $dateArr['day'] . ' ' . mb_strtolower($dateArr['monthFormat']) . ' ' . $dateArr['year'];
        return $dateArr;
    }
}