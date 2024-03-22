<?php
use Core\Route;
return [
    new Route('/', 'index', 'index'),
    new Route('/category/:slug/',   'category', 'index'),
//    new Route('/product/:slug/',   '', 'index'),
];