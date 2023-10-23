<?php

namespace QuarkMvc\middlewares;

class DemoMiddleware implements IMiddleware
{
    public function handle(array $params): void
    {
       // echo "DemoMiddleware handle";
    }
    public function before(array $params): void
    {
        // TODO: Implement before() method.
    }
    public function after(array $params): void
    {
        // TODO: Implement after() method.
    }
}