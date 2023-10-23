<?php

namespace QuarkMvc\app;

class SecurityHelper
{
    public static function generateToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        return $token;
    }
    public static function checkToken(string $token): bool
    {
        if (isset($_SESSION['csrf_token']) && $token === $_SESSION['csrf_token']) {
            unset($_SESSION['csrf_token']);
            return true;
        } else {
            return false;
        }
    }

    public static function XSS_Filter(string $string): string
    {
        $string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
        return $string;
    }
    public static function XSS_Filter_Array(array $array): array
    {
        foreach ($array as $key => $value) {
            $array[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
        return $array;
    }
    public static function XSS_Filter_Json(string $json): string
    {
        $json = json_decode($json, true);
        foreach ($json as $key => $value) {
            $json[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
        return json_encode($json);
    }
    public static function SQLi_Filter(string $string): string
    {
        $string = addslashes($string);
        return $string;
    }
    public static function SQLi_Filter_Array(array $array): array
    {
        foreach ($array as $key => $value) {
            $array[$key] = addslashes($value);
        }
        return $array;
    }
    public static function SQLi_Filter_Json(string $json): string
    {
        $json = json_decode($json, true);
        foreach ($json as $key => $value) {
            $json[$key] = addslashes($value);
        }
        return json_encode($json);
    }


}