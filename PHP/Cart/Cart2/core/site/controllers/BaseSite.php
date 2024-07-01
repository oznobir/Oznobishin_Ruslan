<?php

namespace core\site\controllers;

use core\base\controllers\BaseController;
use core\base\exceptions\DbException;
use core\base\exceptions\RouteException;
use core\site\models\Model;

abstract class BaseSite extends BaseController
{
    /** @uses  pagination */
    protected ?Model $model = null;
    protected ?string $table = null;
    protected array $set;
    protected array $menu;
    protected array $socials;
    protected ?array $sPagination;

    protected string $breadcrumbs;
    protected array $cart;

    /**
     * @throws DbException
     */
    protected function inputData(): void
    {
        $this->init();
        if (!$this->model) $this->model = Model::instance();
        $this->set = $this->model->select('settings', [
            'order' => ['id'], 'limit' => 1
        ]);
        if ($this->set) $this->set = $this->set[0];
        $this->menu['catalog'] = $this->model->select('catalog', [
            'where' => ['visible' => '1', 'pid' => null],
            'order' => ['position']
        ]);
        $this->menu['information'] = $this->model->select('information', [
            'where' => ['visible' => 1, 'show_top_menu' => 1],
            'order' => ['position']
        ]);
        $this->socials = $this->model->select('socials', [
            'where' => ['visible' => 1],
            'order' => ['position']
        ]);
        if(!$this->isAjax() && !$this->isPost()){
            $this->getCartData();
        }
    }

    /**
     * @throws RouteException
     * @uses outputData
     */
    protected function outputData(): false|string
    {
        $this->breadcrumbs = $this->render(SITE_TEMPLATE . 'include/breadcrumbs');
        $this->content = $this->render();
        $this->header = $this->render(SITE_TEMPLATE . 'include/header');
        $this->footer = $this->render(SITE_TEMPLATE . 'include/footer');

        return $this->render(SITE_TEMPLATE . 'layout/default');
    }

    /**
     * @throws DbException
     */
    protected function addToCart($id, $qty): array
    {
        if (!$id) return ['success' => 0, 'message' => 'Отсутствует ID товара'];
        $data = $this->model->select('goods', [
            'fields' => ['id'],
            'where' => ['id' => $id, 'visible' => 1],
            'limit' => 1,
        ]);
        if (!$data) return ['success' => 0, 'message' => 'Отсутствует товар'];
        $cart = &$this->getCart();
        $cart[$id] = $qty;
        $this->updateCookieCart($cart);
        $res = $this->getCartData(true);

        if ($res && !empty($res['goods'][$id])) {
            $res['current'] = $res['goods'][$id];
        }
        return $res;
    }

    /**
     * @param bool $cartChanged
     * @return array|null
     * @throws DbException
     */
    protected function getCartData(bool $cartChanged = false): ?array
    {
        if (!empty($this->cart) && !$cartChanged) return $this->cart;

        $cart = &$this->getCart();
        if (empty($cart)) {
            $this->clearCart();
            return null;
        }
        $var = false;
        $goods = $this->model->getGoods([
            'where' => ['id' => array_keys($cart), 'visible' => 1],
            'operand' => ['IN', '=']
        ], $var, $var);

        if (empty($goods)) {
            $this->clearCart();
            return null;
        }
        $cartChanged = false;
        foreach ($cart as $id => $qty) {
            if (empty($goods[$id])) {
                unset($cart[$id]);
                $cartChanged = true;
                continue;
            }
            $this->cart['goods'][$id] = $goods[$id];
            $this->cart['goods'][$id]['qty'] = $qty;
        }
        if ($cartChanged) $this->updateCookieCart($cart);

        return $this->totalSum();
    }

    /**
     * @return array|null
     */
    protected function totalSum(): ?array
    {
        if (empty($this->cart['goods'])) {
            $this->clearCart();
            return null;
        }

        $this->cart['total_sum'] = $this->cart['total_old_sum'] = $this->cart['total_qty'] = 0;
        foreach ($this->cart['goods'] as $item) {
            $this->cart['total_qty'] += $item['qty'];
            $sum = round($item['qty'] * $item['price'], 2);
            $this->cart['total_sum'] += $sum;
            if (empty($item['old_price'])) $this->cart['total_old_sum'] += $sum;
            else $this->cart['total_old_sum'] += round($item['qty'] * $item['old_price'], 2);
        }
        return $this->cart;
    }

    /**
     * @return void
     */
    public function clearCart(): void
    {
        unset($_COOKIE['cart'], $_SESSION['cart']);
        $this->cart = [];
        if (defined('CART') || strtolower(CART) === 'cookie') {
            setcookie('cart', '', 1, PATH);
        }
    }

    /**
     * @param array $cart
     * @return void
     */

    protected function updateCookieCart(array $cart): void
    {
//        $cart = &$this->getCart();
        if (defined('CART') && strtolower(CART) === 'cookie') {
            setcookie('cart', json_encode($cart), time() + 3600 * 2, PATH);
        }
    }

    protected function &getCart()
    {
        if (defined('CART') && strtolower(CART) === 'cookie') {
            if (!isset($_COOKIE['cart'])) $_COOKIE['cart'] = [];
            else $_COOKIE['cart'] = is_string($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : $_COOKIE['cart'];
            return $_COOKIE['cart'];
        } else {
            if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
            return $_SESSION['cart'];
        }
    }

    protected function pagination(array $pag, array|string $url = null, ?array $icons = null, string $class = ''): void
    {
        if ($url) {
            $firstUrl = $this->getUrl($url);
            $addClass = $class;
        } else {
            $url = ['catalog' => $this->parameters['alias'] ?? ''];
            $firstUrl = $this->getUrl($url);
            $addClass = 'catalog';
        }
        if (!$icons) $icons = [
            'first' => '<<',
            'back' => '<',
            'forward' => '>',
            'last' => '>>',
        ];
        $queryString = $_GET ?? [];
        if (isset($_GET['page'])) unset($queryString['page']);

        if (empty($queryString)) $str = $firstUrl . '?page=';
        else $str = $this->getUrl($url, $queryString) . END_SLASH . '&page=';

        foreach ($pag as $key => $item) {
            if (is_array($item)) {
                foreach ($item as $value) {
                    if ($key == 'previous' && $value === 1) $href = $firstUrl;
                    else $href = $str . $value;
                    $this->showLinkPagination($value, $href, $addClass);
                }
            } elseif ($key == 'current') {
                $this->showLinkPagination($item, '', $addClass);
            } else {
                if (($key == 'first' || $key == 'back') && $item === 1) $href = $firstUrl;
                else $href = $str . $item;
                $this->showLinkPagination($icons[$key], $href, $addClass);
            }
        }
    }

    protected function showLinkPagination($name, $href = '', $addClass = 'catalog'): void
    {
        if ($href) echo <<<TEXT
                            <a href="$href" class="$addClass-section-pagination__item">
                                $name
                            </a>
TEXT;
        else echo <<<TEXT
                            <div class="$addClass-section-pagination__item pagination-current">
                                $name
                            </div>
TEXT;
    }

    /**
     * @param string $img
     * @param bool $tag
     * @return string
     */
    protected function img(string $img = '', bool $tag = false): string
    {
        //        return PATH . IMAGES_DIR . $img;
        $path = '';
        if (!$img && is_dir($_SERVER['DOCUMENT_ROOT'] . PATH . IMAGES_DIR)) {
            $dir = scandir($_SERVER['DOCUMENT_ROOT'] . PATH . IMAGES_DIR);
            $imgArr = preg_grep('/' . $this->getController() . '\./i', $dir) ?:
                preg_grep('/default\./i', $dir);
            if ($imgArr) $path = PATH . IMAGES_DIR . array_shift($imgArr);
        } elseif ($img)
            $path = PATH . UPLOAD_DIR . $img;

        if ($tag) return '<img src="' . $path . '" alt="image" title="image">';

        return $path;
    }

    /**
     * @param array|string $url
     * @param array|string $queryString
     * @return string
     * @uses getUrl
     */
    protected function getUrl(array|string $url = '', array|string $queryString = ''): string
    {
        $str = '';
        if ($queryString) {
            if (is_array($queryString)) {
                foreach ($queryString as $key => $item) {
                    $str .= !$str ? '?' : '&';
                    if (is_array($item)) {
                        $key .= '[]';
                        foreach ($item as $v)
                            $str .= $key . '=' . $v . '&';
                        $str = rtrim($str, '&');
                    } else $str .= $key . '=' . $item;
                }
            } else {
                if (!str_starts_with($queryString, '?')) $str = '?' . $str;
                else $str .= $queryString;
            }
        }

        if (is_array($url)) {
            $urlStr = '';
            foreach ($url as $key => $item) {
                if ($item) {
                    if (!is_numeric($key)) $urlStr .= $key . '/' . $item . '/';
                    else $urlStr .= $item . '/';
                } elseif (!is_numeric($key)) $urlStr .= $key . '/';
            }
            $url = $urlStr;
        }
        if (!$url || $url === '/')
            return PATH . $str;
        if (preg_match('/^\s*https?:\/\//i', $url))
            return $url . $str;

        return preg_replace('/\/{2,}/', '/', PATH . trim($url, '/') . END_SLASH . $str);
    }

    /**
     * @param string $str
     * @return array|false
     * @uses spaceArr
     */
    protected function spaceArr(string $str): false|array
    {
        return preg_split('/\s+/', $str, 0, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * @param int $counter
     * @param array|string $words
     * @return mixed|string|null
     * @uses wordsCounter
     */
    protected function wordsCounter(int $counter, array|string $words = 'years'): mixed
    {
        $arr = [
            'years' => ['лет', 'год', 'года'],
        ];
        if (is_array($words)) $arr = $words;
        else $arr = $arr[$words] ?? array_shift($arr);

        if (!$arr) return null;

        $char = (int)substr($counter, -1);
        $counter = (int)substr($counter, -2);

        if (($counter >= 10 && $counter <= 20) || ($char >= 5 && $char <= 9) || !$char)
            return $arr[0] ?? null;
        elseif ($char === 1)
            return $arr[1] ?? null;
        else
            return $arr[2] ?? null;
    }

    /**
     * @param array $data
     * @param array $parameters
     * @param string $template
     * @return void
     * @throws RouteException
     * @uses showOneItems
     */
    protected function showOneItems(array $data, array $parameters = [], string $template = 'cardOneGoods'): void
    {
        if (!empty($data)) {
            echo $this->render(SITE_TEMPLATE . 'include/' . $template, compact('data', 'parameters'));
        }
    }
}