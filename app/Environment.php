<?php

namespace QuarkMvc\app;

class Environment
{
    public static function get(string $key): string
    {
        $path=Quark::$SUPER_DIR."\.env";
        if (!file_exists($path)) {
            Error::renderError("EnvironmentFileNotFound");
        }
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
                Error::renderError("EnvironmentKeyNotFound","key=>".$key);
            }
        } else {
            Error::renderError("EnvironmentPermission");
        }
        return "";
    }
}