<?php

if ($_SERVER['REQUEST_METHOD'] == 'GET')
    return [
        ['/', 'index', 'index'],
        ['/category/:slug/', 'category', 'index'],
        ['/product/:slug/', 'product', 'index'],
        ['/cart/', 'cart', 'index'],
        ['/cart/add/:id/:count/', 'cart', 'add'],
        ['/cart/remove/:id/', 'cart', 'remove'],
        ['/user/', 'user', 'index'],
        ['/user/logout/', 'user', 'logout'],
        ['/user/unregister/', 'user', 'unregister'],
        ['/admin/', 'admin', 'index'],
        ['/admin/products/', 'admin', 'products'],
        ['/admin/orders/', 'admin', 'orders'],
    ];
if ($_SERVER['REQUEST_METHOD'] == 'POST')
    return [
        ['/user/register/', 'user', 'register'],
        ['/user/login/', 'user', 'login'],
        ['/user/update/', 'user', 'update'],
        ['/cart/order/', 'cart', 'order'],
        ['/admin/category/add/', 'admin', 'addcategory'],
        ['/admin/category/update/', 'admin', 'updatecategory'],
        ['/admin/product/add/', 'admin', 'addproduct'],
        ['/admin/product/update/', 'admin', 'updateproduct'],
        ['/admin/product/image/', 'admin', 'uploadimage'],
        ['/admin/order/status/', 'admin', 'orderstatus'],
        ['/admin/order/date/', 'admin', 'orderdate'],
    ];