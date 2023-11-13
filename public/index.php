<?php
require_once __DIR__ . '/../vendor/autoload.php';
use QuarkMvc\app\Quark;
$quark = new Quark(true);

$quark->routes->setRoute('/', 'HomeController',"get");
$quark->routes->addMiddleware('/', 'DemoMiddleware',"get");


$quark->routes->setRoute('register', 'RegisterController',"get");
$quark->routes->setRoute('register', 'RegisterController',"post");
$quark->routes->setRoute('commands', 'CommandsController',"get");
$quark->routes->set404Route('Custom404Controller');

$quark->run();


