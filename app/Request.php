<?php

namespace QuarkMvc\app;

class Request
{


    public function currentPath(): string
    {
        $url=$_SERVER['REQUEST_URI'] ?? '/';
        $position=strpos($url,'?');
        if($position===false){
            return $url;
        }
        return substr($url,0,$position);
    }
    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    public static function endStatus(int $code): void
    {
        http_response_code($code);
    }
    public static function redirect(string $url): void
    {
        header("Location: $url");
        die();
    }
    public static function redirectWithStatus(string $url, int $code): void
    {
        http_response_code($code);
        header("Location: $url");
        die();
    }

}