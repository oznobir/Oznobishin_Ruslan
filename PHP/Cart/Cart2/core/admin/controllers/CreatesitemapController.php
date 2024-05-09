<?php

namespace core\admin\controllers;

use core\base\controllers\BaseMethods;
use core\base\settings\Settings;
use JetBrains\PhpStorm\NoReturn;


class CreatesitemapController extends BaseAdmin
{
    use BaseMethods;

    protected array $linkArr = [];
    protected string $parsingLogFile = 'parsing_log.txt';
    protected array $extFiles = ['jpg', 'png', 'jpeg', 'gif', 'pdf'];
    protected array $messages = [];
    protected array $filterArr = [
        'url' => ['order'],
        'get' => ['vanya']
    ];

    #[NoReturn] protected function inputData(): void
    {
//        file_get_contents();
//        get_headers();
        if (!$this->messages) $this->messages = include $_SERVER['DOCUMENT_ROOT'] . PATH . Settings::get('messages') . 'informationMessages.php';
        if (!function_exists('curl_init')) {
            $this->writeLog('Отсутствует библиотека CURL', 'log.txt', 'Notice');
            $_SESSION['res']['answer'] = '<div class="error">' . $this->messages['curlFail'] . '</div>';
            $this->redirect();
        }
        set_time_limit(0);

        if (file_exists($_SERVER['DOCUMENT_ROOT'] . PATH . 'log/' . $this->parsingLogFile))
            @unlink($_SERVER['DOCUMENT_ROOT'] . PATH . 'log/' . $this->parsingLogFile);

//        $url = SITE_URL;
        $url = 'https://s13.ru';
//        $url = 'https://www.it-academy.by';
//        $url = 'https://www.php.net';

        $this->parsing($url);
        $this->creatSitemap();
        if (isset($_SESSION['res']['answer']))
            $_SESSION['res']['answer'] = '<div class="success">' . $this->messages['curlSuccess'] . '</div>';
        $this->redirect();
    }

    protected function parsing($url, $index = 0): void
    {
        $curl = curl_init();
        // однопоточный режим
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 120);
        curl_setopt($curl, CURLOPT_RANGE, 0 - 4194304);

        $out = curl_exec($curl);
        curl_close($curl);
        if (!preg_match('/content-type:\s+text\/html/ui', $out)) {
            unset($this->linkArr[$index]);
            $this->linkArr = array_values($this->linkArr);
            return;
        }
        if (!preg_match('/HTTP\/\d\.?\d?\s+20\d/ui', $out)) {
            unset($this->linkArr[$index]);
            $this->linkArr = array_values($this->linkArr);
            $this->writeLog('Не корректная ссылка при создании карты сайта - ' . $url, $this->parsingLogFile, 'Notice');
            $_SESSION['res']['answer'] = '<div class="error">' . sprintf($this->messages['linkFail'], $url) . '</div>';
            return;
        }
        /*        $str = "<a class=\"main\" href =   'my/href' data-id='12'>";
                  preg_match_all('/<a\s*?[^>]*?href\s*?=\s*?(["\'])(.+?)\1[^>]*?>/ui', $str, $links);     */
        preg_match_all('/<a\s*?[^>]*?href\s*?=\s*?(["\'])(.+?)\1[^>]*?>/ui', $out, $links);
        if ($links[2]) {
            foreach ($links[2] as $link) {
                if ($link === '/' || $link === SITE_URL . '/') continue;
                foreach ($this->extFiles as $ext) {
                    if ($ext) {
                        $ext = preg_quote($ext, '/');
                        if (preg_match('/' . $ext . '\s*?$|\?[^\\\/]/ui', $link))
                            continue 2;
                    }
                }
                if (str_starts_with($link, '/'))
                    $link = SITE_URL . $link;
                if (!in_array($link, $this->linkArr) &&
                    $link !== '#' && str_starts_with($link, SITE_URL)) {
                    if ($this->filter($link)) {
                        $this->linkArr[] = $link;
                        $this->parsing($link, count($this->linkArr) - 1);
                    }
                }
            }
        }
    }

    protected function filter($link): bool
    {
//        $link = "https://aerttx.ru/ord/id?vanya=Desc&amp:name=111";
        if ($this->filterArr) {
            foreach ($this->filterArr as $type => $values) {
                if ($values) {
                    foreach ($values as $item) {
                        $item = str_replace('/', '\/', $item);
                        if ($type === 'url') {
                            if (preg_match('/^[^\\\?]*' . $item . '/ui', $link))
                                return false;
                        }
                        if ($type === 'get') {
                            if (preg_match('/(\?|&amp;|=|&)' . $item . '(\?|&amp;|=|&|$)/ui', $link))
                                return false;
                        }
                    }
                }
            }
        }
        return true;
    }

    protected function creatSitemap()
    {

    }
}