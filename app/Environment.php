<?php

namespace QuarkMvc\app;

class Environment
{
    public static function get(string $key): string
    {
        $path=Quark::$SUPER_DIR."\.env";
        $file = fopen($path, "r");
        if ($file) {
            $parse = [];
            while (($line = fgets($file)) !== false) {
                $line = explode("=", $line);
                $parse[$line[0]] = $line[1];
            }
            fclose($file);
            if (array_key_exists($key, $parse)) {
                return $parse[$key];
            } else {
                Error::renderError("Environment key not found","key=>".$key);
            }
        } else {
            Error::renderError("Environment file not found");
        }
        return "";
    }
}