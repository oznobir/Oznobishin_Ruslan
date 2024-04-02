<?php

use Core\Route;

if ($_SERVER['REQUEST_METHOD'] === 'GET')
    return [
        new Route('/', 'index', 'index'),
        new Route('/category/:slug/', 'category', 'index'),
        new Route('/product/:slug/', 'product', 'index'),
        new Route('/cart/', 'cart', 'index'),
        new Route('/cart/add/:id/:count/', 'cart', 'add'),
        new Route('/cart/remove/:id/', 'cart', 'remove'),
        new Route('/user/', 'user', 'index'),
        new Route('/user/logout/', 'user', 'logout'),
        new Route('/cart/count/:id/:count/', 'cart', 'count'),

        new Route('/d/', 'index', 'd'),
    ];
if ($_SERVER['REQUEST_METHOD'] === 'POST')
    return [
        new Route('/user/register/', 'user', 'register'),
        new Route('/user/login/', 'user', 'login'),
        new Route('/user/update/', 'user', 'update'),
        new Route('/cart/order/', 'cart', 'order'),
    ];