<?php
require_once __DIR__ . '/../vendor/autoload.php';
use QuarkMvc\app\Quark;
$quark = new Quark();
$quark->routes->setRoute('register', 'RegisterController',"get");
$quark->routes->setRoute('register', 'RegisterController',"post");
$quark->routes->setRoute('home', 'HomeController',"get");
$quark->routes->setRoute('commands', 'CommandsController',"get");

$quark->routes->addMiddleware('home', 'DemoMiddleware',"get");
$quark->routes->set404Route('Custom404Controller');

$quark->run();


