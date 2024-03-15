<?php
use \Core\Route;
return [
    new Route('/', 'menu', 'show'),
    new Route('/menu/',   'menu', 'show'),
    new Route('/page/:p/', 'page', 'show'),
];