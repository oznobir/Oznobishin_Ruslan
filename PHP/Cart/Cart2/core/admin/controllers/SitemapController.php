<?php

namespace core\admin\controllers;

use core\base\controllers\BaseMethods;
use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;
use DateTime;
use DOMDocument;
use DOMException;


class SitemapController extends BaseAdmin
{
    use BaseMethods;

    protected array $all_links = [];
    protected array $temp_links = [];
    protected array $bad_links = [];
    protected int $maxLinks = 4000;
    protected string $parsingLogFile = 'parsing_log.txt';
    protected array $extFiles = ['mp4', 'jpg', 'png', 'jpeg', 'gif', 'pdf'];
    protected array $filterArr = [
        'url' => [],
        'get' => []
    ];


    /**
     * @param int $linksCounter
     * @param bool $redirect
     * @return void
     * @throws DOMException
     * @throws DbException|RouteException
     */
    public function inputData(int $linksCounter = 1, bool $redirect = true): void
    {
//        file_get_contents();
//        get_headers();
        if (!$this->userId) $this->exec();
        if (!function_exists('curl_init'))
            $this->cancel(0, $this->info['curlFail'], '', true);

        if (!$this->checkParsingTable())
            $this->cancel(0, $this->info['parsingTableFail'], '', true);

        set_time_limit(0);

        $table_rows = [];
        $reserve = $this->model->select('parsing_table');
        if (!empty($reserve)) {
            foreach ($reserve[0] as $name => $item) {
                $table_rows[$name] = '';
                if ($item) $this->$name = json_decode($item);
                elseif ($name === 'temp_links' || $name === 'all_links') $this->$name = [SITE_URL];
            }
        } else $this->all_links = $this->temp_links = [SITE_URL];

        $linksCounter = $this->num($linksCounter);
        $this->maxLinks = (int)$linksCounter > 1 ? ceil($this->maxLinks / $linksCounter) : $this->maxLinks;
        while ($this->temp_links) {
            $countTempLinks = count($this->temp_links);
            $links = $this->temp_links;
            $this->temp_links = [];
            if ($countTempLinks > $this->maxLinks) {
                $links = array_chunk($links, ceil($countTempLinks / $this->maxLinks));
                $countChink = count($links);
                for ($i = 0; $i < $countChink; $i++) {
                    $this->parsing($links[$i]);
                    unset($links[$i]);
                    if ($links) {
                        foreach ($table_rows as $name => $item) {
                            if ($name === 'temp_links') $table_rows[$name] = json_encode(array_merge(...$links));
                            else $table_rows[$name] = json_encode($this->$name);
                        }
                        $this->model->add('parsing_table', [
                            'fields' => $table_rows
                        ]);
                    }
                }
            } else {
                $this->parsing($links);
            }
            foreach ($table_rows as $name => $item)
                $table_rows[$name] = json_encode($this->$name);

            $this->model->add('parsing_table', [
                'fields' => $table_rows
            ]);
        }
        foreach ($table_rows as $name => $item)
            $table_rows[$name] = '';

        $this->model->edit('parsing_table', [
            'fields' => $table_rows
        ]);
        if ($this->all_links) {
            foreach ($this->all_links as $key => $link) {
                if (!$this->filter($link) || in_array($link, $this->bad_links))
                    unset($this->all_links[$key]);
            }
        }
        $this->createSitemap();
        if ($redirect) {
            $_SESSION['res']['answer'] = '<div class="success">' . sprintf($this->info['curlSuccess'], count($this->all_links)) . '</div>';
            $this->redirect();
        } else {
            $this->cancel(1, sprintf($this->info['curlSuccess'], count($this->all_links)), '', true);
        }
    }

    /**
     * @param array $urls
     * @return void
     */
    protected function parsing(array $urls): void
    {
        if (!$urls) return;
        $curlMulti = curl_multi_init();
        $curl = [];
        foreach ($urls as $i => $url) {
            $curl[$i] = curl_init();
            curl_setopt($curl[$i], CURLOPT_URL, $url);
            curl_setopt($curl[$i], CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl[$i], CURLOPT_HEADER, true);
            curl_setopt($curl[$i], CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl[$i], CURLOPT_TIMEOUT, 120);

            curl_multi_add_handle($curlMulti, $curl[$i]);
        }

        do {
            $status = curl_multi_exec($curlMulti, $active);
            $info = curl_multi_info_read($curlMulti);
            if (false !== $info) {
                if ($info['result'] !== 0) {
                    $i = array_search($info['handle'], $curl);
                    $error = curl_errno($curl[$i]);
                    $message = curl_error($curl[$i]);
                    $header = curl_getinfo($curl[$i]);
                    if ($error != 0) {
                        $this->cancel(0, sprintf($this->info['mesCurlFail'], $header['url'],
                            $header['http_code'], $error, $message));
                    }
                }
            }
            if ($status > 0) $this->cancel(0, curl_multi_strerror($status));
        } while ($status === CURLM_CALL_MULTI_PERFORM || $active);

//        $result = [];
        foreach ($urls as $i => $url) {
            $result[$i] = curl_multi_getcontent($curl[$i]);
            curl_multi_remove_handle($curlMulti, $curl[$i]);
            curl_close($curl[$i]);
            if (!preg_match('/content-type:\s+text\/html/ui', $result[$i])) {
                $this->bad_links[] = $url;
                $this->cancel(0, sprintf($this->info['typeCurlFail'], $url));
                continue;
            }
            if (!preg_match('/HTTP\/\d\.?\d?\s+20\d/ui', $result[$i])) {
                $this->bad_links[] = $url;
                $this->cancel(0, sprintf($this->info['codeCurlFail'], $url));
                continue;
            }

            $this->createLinks($result[$i]);
        }
        curl_multi_close($curlMulti);
    }

    /**
     * @param $content
     * @return void
     */
    private function createLinks($content): void
    {
        if ($content) {
            /*  $str = "<a class=\"main\" href =   'my/href' data-id='12'>";
                preg_match_all('/<a\s*?[^>]*?href\s*?=\s*?(["\'])(.+?)\1[^>]*?>/ui', $str, $links);     */

            preg_match_all('/<a\s*?[^>]*?href\s*?=\s*?(["\'])(.+?)\1[^>]*?>/ui', $content, $links);
            if ($links[2]) {
                foreach ($links[2] as $link) {
                    if ($link === '/' || $link === SITE_URL . '/') continue;
                    foreach ($this->extFiles as $ext) {
                        if ($ext) {
                            $ext = preg_quote($ext, '/');
                            if (preg_match('/' . $ext . '(\s*?$|\?[^\/]*$)/ui', $link))
                                continue 2;
                        }
                    }
                    if (str_starts_with($link, '/'))
                        $link = SITE_URL . $link;
                    $strUrl = str_replace('.', '\.', str_replace('/', '\/', SITE_URL));
                    if (!in_array($link, $this->bad_links)
                        && !preg_match('/^(' . $strUrl . ')?\/?#[^\/]*?$/ui', $link)
                        && str_starts_with($link, SITE_URL)
                        && !in_array($link, $this->all_links)) {

                        $this->temp_links[] = $link;
                        $this->all_links[] = $link;
                    }
                }
            }
        }

    }

    /**
     * @param $link
     * @return bool
     */
    protected function filter($link): bool
    {
        if ($this->filterArr) {
            foreach ($this->filterArr as $type => $values) {
                if ($values) {
                    foreach ($values as $item) {
                        $item = str_replace('/', '\/', $item);
                        if ($type === 'url') {
                            if (preg_match('/^[^\?]*' . $item . '/ui', $link))
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

    /**
     * @param int $success
     * @param string $messageAdmin
     * @param string $messageLog
     * @param bool $exit
     * @return array|void
     */
    protected function cancel(int $success = 0, string $messageAdmin = '', string $messageLog = '', bool $exit = false)
    {
        $exitArr = [];
        $exitArr['success'] = $success;
        $exitArr['message'] = $messageAdmin ?: $this->info['parsingFail'];
        $messageLog = $messageLog ?: $exitArr['message'];
        $class = 'success';
        if (!$exitArr['success']) {
            $class = 'error';
            $this->writeLog($messageLog, $this->parsingLogFile);
        }
        if ($exit) {
            $exitArr['message'] = '<div class="' . $class . '">' . $exitArr['message'] . '</div>';
            return $exitArr;
        }
    }

    /**
     * @throws DOMException
     */
    protected function createSitemap(): void
    {
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = true;

        $root = $dom->createElement('urlset');
        $root->setAttribute('xmlns:xsi', 'http://w3.org/2001/XMLSchema-instance');
        $root->setAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');
        $root->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $dom->appendChild($root);
        $sxe = simplexml_import_dom($dom);
        if ($this->all_links) {
            $lastMod = (new DateTime())->format('c');
            foreach ($this->all_links as $link) {
                $elem = trim(mb_substr($link, mb_strlen(SITE_URL)), '/');
                $elem = explode('/', $elem);
                $count = '0.' . (count($elem) - 1);
                $priority = 1 - (float)$count;
                if ($priority == 1) $priority = '1.0';
                $urlMain = $sxe->addChild('url');
                $urlMain->addChild('loc', htmlspecialchars($link));
                $urlMain->addChild('lastmod', $lastMod);
                $urlMain->addChild('changefreg', 'weekly');
                $urlMain->addChild('priority', $priority);
                $urlMain->addChild('lastmod', $lastMod);
            }
        }
        $dom->save($_SERVER['DOCUMENT_ROOT'] . PATH . 'sitemap.xml');
    }

    /**
     * @return bool true - в БД есть (создана/очищена) пустая таблица parsing_table (all_links, temp_links)
     * @throws DbException
     */

    private function checkParsingTable(): bool
    {
        $tables = $this->model->showTables();
        if (!in_array('parsing_table', $tables)) {
            $query = 'CREATE TABLE parsing_table (all_links longtext, temp_links longtext, bad_links longtext)';
            if (!$this->model->query($query, 'default') || !$this->model->add('parsing_table', [
                    'fields' => ['all_links' => '', 'temp_links' => '', 'bad_links' => '']
                ])) {
                return false;
            }
        }
        return true;
    }
}