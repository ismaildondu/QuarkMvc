<?php

namespace QuarkMvc\controllers;
use QuarkMvc\app\Render;
use QuarkMvc\app\SecurityHelper;


class HomeController implements IController
{

    public function index(array $params): void
    {
        // use QuarkMvc\app\Environment;
        // $KEY=Environment::get("SIMPLE_KEY");

        $csrf=SecurityHelper::generateToken();
        $this->render('home',["params"=>$csrf]);
    }
    public function render(string $view, array $params = [],int $statusCode=200): void
    {
        Render::render($view, $params,$statusCode);
    }

    public function before(array $params): void
    {

    }
    public function after(array $params): void
    {

    }
}