<?php

defined('W_ACCESS') or die('Нет доступа');
const HOST = 'localhost';
const USER = 'root';
const PASS = '';
const DB = 'myshop2';
const SITE_URL = 'http://myshop2.by';
//const SITE_URL = 'https://k12.by';
//const SITE_URL = 'https://oknaminsk24.by';
const PATH = '/';
const SITE_TEMPLATE = 'templates/default/';
const ADMIN_TEMPLATE = 'core/admin/views/';
const UPLOAD_DIR = 'files/';
const COOKIE_VERSION = '1.0.0';
const COOKIE_TIME = 60;
const CRYPT_KEY = 'd825e55c785e13ab385248818457e7bec9f0e02e242b7585a94ee658e88d75ee';
const BLOCK_TIME = 3;

const QTY = 8;
const QTY_LINKS = 3;

const ADMIN_CSS_JS = [
    'styles' => ['css/main.css'],
    'scripts' => [
        'js/functions.js',
        'js/scripts.js',
        'js/tinymce/tinymce.min.js',
        'js/tinymce/tinymce_init.js'
    ],
];
const SITE_CSS_JS = [
    'styles' => ['css/main.css'],
    'scripts' => [],
];
