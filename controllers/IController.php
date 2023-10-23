<?php

namespace QuarkMvc\controllers;

interface IController
{
    /*
     *  ==================
     *  Controller Life Cycle
     *  -> before
     *  -> middleware
     *  -> index logic
     *  -> after
     *  ==================
     *
     *  index $params["PATH"] +
     *  index $params["GET"][] +
     *  index $params["PARAMS"][] +
     *  index $params["POST"][] +
     *  index $params["COOKIES"][] +
     *  index $params["FILES"][] +
     *  index $params["SERVER"][] +
     *  index $params["REQUEST"][] +
     *
     *  public function index(array $params): void;
     *      ...code...
     *      $this->render('home',["twigVarName"=>$params["PARAMS"][0]]);
     *
     * public function render(string $view, array $params = [],int $statusCode=200): void
     *     ...code...
     *    Render::render($view, $params);
     */
    public function index(array $params): void;
    public function render(string $view, array $params = [],int $statusCode=200 ): void;
    public function before(array $params): void;
    public function after(array $params): void;
}