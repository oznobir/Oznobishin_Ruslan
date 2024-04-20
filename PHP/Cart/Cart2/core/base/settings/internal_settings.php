<?php

use core\base\exceptions\RouteException;

defined('W_ACCESS') or die('Нет доступа');
const SITE_TEMPLATE = 'templates/default/';
const ADMIN_TEMPLATE = 'core/admin/views/';
const  COOKIE_VERSION = '1.0.0';
const  COOKIE_TIME = 60;
const CRYPT_KEY = '';
const BLOCK_TIME = 3;

const QTY = 8;
const QTY_LINKS = 3;

const ADMIN_CSS_JS = [
    'styles' => [],
    'scripts' => [],
];
const SITE_CSS_JS = [
    'styles' => [],
    'scripts' => [],
];
