<?php

namespace QuarkMvc\controllers;
use QuarkMvc\app\Render;

class Custom404Controller implements IController
{
        public function index(array $params): void
        {
            $this->render('404',["path"=>$params["PATH"]]);
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