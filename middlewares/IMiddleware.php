<?php

namespace QuarkMvc\middlewares;

interface IMiddleware
{
    /*
     *  ==================
     *  Middleware Life Cycle
     *  -> before
     *  -> handle
     *  -> after
     *  ==================
     */
    public function handle(array $params): void;
    public function before(array $params): void;
    public function after(array $params): void;
}