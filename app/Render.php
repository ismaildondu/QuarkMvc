<?php

namespace QuarkMvc\app;

class Render
{
    public static function render(string $view, array $params = [],int $statusCode=200): void
    {
        if(self::checkView($view)){
            echo Quark::$render->render($view . ".twig", $params);
            http_response_code($statusCode);
        }else{
            Error::renderError("ViewNotFound",$view);
        }

    }
    public static function checkView(string $view): bool
    {
        $view = Quark::$SUPER_DIR . "/views/" . $view . ".twig";
        if (file_exists($view)) {
            return true;
        } else {
            return false;
        }
    }
}