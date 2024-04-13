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
        new Route('/user/unregister/', 'user', 'unregister'),
        new Route('/admin/', 'admin', 'index'),
        new Route('/admin/products/', 'admin', 'products'),

        new Route('/d/', 'index', 'd'),
    ];
if ($_SERVER['REQUEST_METHOD'] === 'POST')
    return [
        new Route('/user/register/', 'user', 'register'),
        new Route('/user/login/', 'user', 'login'),
        new Route('/user/update/', 'user', 'update'),
        new Route('/cart/order/', 'cart', 'order'),
        new Route('/admin/category/add/', 'admin', 'addcategory'),
        new Route('/admin/category/update/', 'admin', 'updatecategory'),
        new Route('/admin/product/add/', 'admin', 'addproduct'),
        new Route('/admin/product/update/', 'admin', 'updateproduct'),
        new Route('/admin/product/image/', 'admin', 'uploadimage'),
    ];