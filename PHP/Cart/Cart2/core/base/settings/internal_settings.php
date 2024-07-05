<?php

defined('W_ACCESS') or die('Нет доступа');
const HOST = 'localhost';
const USER = 'root';
const PASS = '';
const DB = 'myshop2';
const SITE_URL = 'http://myshop2.by';
//const SITE_URL = 'https://oknaminsk24.by';
const PATH = '/';
const END_SLASH = ''; // со слэшем подумать
const SITE_TEMPLATE = 'templates/default/';
const ADMIN_TEMPLATE = 'core/admin/views/';
const UPLOAD_DIR = 'files/';
const IMAGES_DIR = 'files/default_images/';
const CART = 'cookie'; // else - sessions
const COOKIE_VERSION = '1.0.0';
const COOKIE_TIME = 60;
const CRYPT_KEY = 'd825e55c785e13ab385248818457e7bec9f0e02e242b7585a94ee658e88d75ee';
const BLOCK_TIME = 3;

const QTY = 4;
const QTY_LINKS = 2;

const ADMIN_CSS_JS = [
    'styles_our' => ['css/main.css'],
    'scripts_our' => [
        'js/functions.js',
        'js/scripts.js',
        'js/tinymce/tinymce.min.js',
        'js/tinymce/tinymce_init.js'
    ],
];
const SITE_CSS_JS = [
    'styles_external' => [
        'https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700&display=swap&subset=cyrillic',
        'https://fonts.googleapis.com/css?family=Didact+Gothic&display=swap&subset=cyrillic',
        'https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css',
        'https://unpkg.com/swiper/swiper-bundle.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css',
    ],
    'styles_our' => [
        'assets/css/animate.css',
        'assets/css/style.css',
    ],
    'scripts_external' => [
        'https://code.jquery.com/jquery-3.4.1.min.js',
        'https://unpkg.com/swiper/swiper-bundle.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.2.5/gsap.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.0.2/gsap.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/2.1.3/TweenMax.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.7/ScrollMagic.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.7/plugins/animation.gsap.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.7/plugins/debug.addIndicators.min.js',
    ],
    'scripts_our' => [
        'assets/js/jquery.maskedinput.min.js',
        'assets/js/TweenMax.min.js',
        'assets/js/ScrollMagic.min.js',
        'assets/js/animation.gsap.min.js',
        'assets/js/bodyscrolllock/bodyScrollLock.min.js',
        'assets/js/app.js',
        'assets/js/script.js',
        'assets/js/showMessage.js',
    ],

];
