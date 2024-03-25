<?php

use Core\Route;

//if ($_SERVER['REQUEST_METHOD'] === 'GET')
    return [
        new Route('/', 'index', 'index'),
        new Route('/category/:slug/', 'category', 'index'),
        new Route('/product/:slug/', 'product', 'index'),
        new Route('/cart/', 'cart', 'index'),
        new Route('/cart/add/:id/', 'cart', 'add'),
        new Route('/cart/remove/:id/', 'cart', 'remove'),

    ];
//if ($_SERVER['REQUEST_METHOD'] === 'POST')
//    return [
//        new Route('/cart/add/:id/', 'cart', 'add'),
//    ];