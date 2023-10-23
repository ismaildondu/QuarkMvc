<?php

namespace QuarkMvc\app;

class Error
{
    private static array $errorDetail=[
        "IControllerImplementationError"=>"IController interface is not implemented",
        "Default404Error"=>"Page not found. This is default 404 error page, if you want to change it, you can add 404 route in your routes. (routes->set404Route)",
        "ViewNotFound"=>"View not found in views folder:",
        "ControllerNotFound"=>"Controller not found:",
        "RouteTypeNotValid"=>"Route type is not valid:",
        "HelperKeyFieldNotValid"=>"Key field not found in rule array:",
        "RuleValueMustBeArray"=>"Rule value must be array:",
        "HelperKeyNotValid"=>"Key not valid in rule array:",
        "RouteNotFound"=>"Route not found:",
        "IMiddlewareImplementationError"=>"IMiddleware interface is not implemented",
        "MiddlewareNotFound"=>"Middleware not found:",
        "Exception"=>"Exception:",
        "CustomErrorMessageKeyNotValid"=>"Custom error message key not valid:",
        "QuarkLocalizorError"=>"Localizor error:"
    ];
    private static array $errorCodes=[
        "IControllerImplementationError"=>500,
        "Default404Error"=>404,
        "ViewNotFound"=>500,
        "ControllerNotFound"=>500,
        "RouteTypeNotValid"=>500,
        "HelperKeyFieldNotValid"=>500,
        "RuleValueMustBeArray"=>500,
        "HelperKeyNotValid"=>500,
        "RouteNotFound"=>404,
        "IMiddlewareImplementationError"=>500,
        "MiddlewareNotFound"=>500,
        "Exception"=>500,
        "CustomErrorMessageKeyNotValid"=>500,
        "QuarkLocalizorError"=>500

    ];

    public static function renderError(string $key,string $additionalContent=""){
        $error=[
            "title"=>$key,
            "content"=>self::$errorDetail[$key]." ".$additionalContent
        ];
        Quark::$render->display('default/error.twig',$error);
        Request::endStatus(self::$errorCodes[$key]);
        die();
    }
    public static function renderNativeError($errno, $errstr, $errfile, $errline){
        $error=[
            "title"=>"Native Error",
            "content"=>"Error: ".$errstr." in file: ".$errfile." on line: ".$errline
        ];
        Quark::$render->display('default/error.twig',$error);
        Request::endStatus(500);
        die();
    }


}