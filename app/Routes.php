<?php

namespace QuarkMvc\app;

use QuarkMvc\app\localizor\Localizor;

class Routes
{
    public static array $routesTypes = [
        "GET" => "GET",
        "POST" => "POST",
        "PUT" => "PUT",
        "DELETE" => "DELETE",
        "PATCH" => "PATCH",
        "OPTIONS" => "OPTIONS",
        "HEAD" => "HEAD",
        "ANY" => "ANY",
    ];
    private array $routes = [];
    public Request $request;
    public function __construct()
    {
        $this->request = new Request();

    }
    public function setRoute(string $route, string $controller,string $type): void
    {
        $type = strtoupper($type);
        $this->checkRouteType($type);
        if ($route[0] !== '/') {
            $route = '/' . $route;
        }
        $route=str_replace(':params','([a-zA-Z0-9\-_]+)',$route);
        $this->routes[$type][$route] = $controller;
    }

    public function set404Route(string $controller): void
    {
        $this->routes['404'] = $controller;
    }
    public function executeRoute():void{
      $path=$this->request->currentPath();
      $method=$this->request->method();
      foreach($this->routes[$method] as $route=>$callBack){
          if(preg_match("@^$route$@",$path,$matches)){
              if(count($matches)==1){
                  $this->executeController($callBack,$this->renderArray($_GET,[],$_POST,$_COOKIE,$_FILES,$_SERVER,$_REQUEST));
                  return;
              }
              if(count($matches)==2){
                  $params=explode(",",$matches[1]);
                  $this->executeController($callBack,$this->renderArray($_GET,$params,$_POST,$_COOKIE,$_FILES,$_SERVER,$_REQUEST));
                  return;
              }
              if(count($matches)>2){
                  $params=[];
                  for($i=1;$i<count($matches);$i++){
                      $params[]=$matches[$i];
                  }
                  $this->executeController($callBack,$this->renderArray($_GET,$params,$_POST,$_COOKIE,$_FILES,$_SERVER,$_REQUEST));
                  return;
              }
          }
      }
        if(isset($this->routes[404])){
            $this->executeController($this->routes[404],$this->renderArray());
            return;
        }else{
            Error::renderError("Default404Error");
            return;
        }
    }

    private function renderArray(array $get=[],array $params=[],array $post=[],array $cookies=[],array $files=[],array $server=[],array $req=[]): array
    {
        $return=[
            "PATH"=>$_SERVER['REQUEST_URI'] ?? '/',
            "PARAMS"=>$params,
        ];
        if(count($get)>0){
            $return["GET"]=$get;
        }
        if(count($post)>0){
            $return["POST"]=$post;
        }
        if(count($cookies)>0){
            $return["COOKIES"]=$cookies;
        }
        if(count($files)>0){
            $return["FILES"]=$files;
        }
        if(count($server)>0){
            $return["SERVER"]=$server;
        }
        if(count($req)>0){
            $return["REQUEST"]=$req;
        }
        return $return;
    }

    private function executeController(string $controllerName, array $params): void
    {
        $controllerName="QuarkMvc\\controllers\\".$controllerName;
        $instance="QuarkMvc\\controllers\\IController";
        if (!class_exists($controllerName)) {
            Error::renderError("ControllerNotFound", $controllerName);
            return;
        }
        $controller=new $controllerName();
        if(!$controller instanceof $instance){
            Error::renderError("IControllerImplementationError",$controllerName);
            return;
        }

        $middleware=$this->routes['middleware'] ?? [];
        ob_start();
        session_name("QUARK_SESSION_ID");
        session_start();
        foreach($middleware as $route=>$middlewares){
            if(preg_match("@^$route$@",$params["PATH"],$matches)){
                foreach($middlewares as $middleware){
                    $middlewareName="QuarkMvc\\middlewares\\".$middleware;
                    $instance="QuarkMvc\\middlewares\\IMiddleware";
                    if (!class_exists($middlewareName)) {
                        Error::renderError("MiddlewareNotFound", $middlewareName);
                        return;
                    }
                    $middlewareInstance=new $middlewareName();
                    if(!$middlewareInstance instanceof $instance){
                        Error::renderError("IMiddlewareImplementationError",$middlewareName);
                        return;
                    }
                    $middlewareInstance->before($params);
                    $middlewareInstance->handle($params);
                    $middlewareInstance->after($params);
                }
            }
        }
        $controller->before($params);
        $controller->index($params);
        $controller->after($params);
        session_write_close();
        ob_end_flush();

    }
    public function addMiddleware(string $route, string $middleware,string $type): void
    {
        $type = strtoupper($type);
        $this->checkRouteType($type);
        if ($route[0] !== '/') {
            $route = '/' . $route;
        }
        $plainRoute=$route;
        $route=str_replace(':params','([a-zA-Z0-9\-_]+)',$route);
        if(!isset($this->routes[$type][$route])){
            Error::renderError("RouteNotFound",$plainRoute." for middleware ".$middleware);
            return;
        }
        $this->routes['middleware'][$route][] = $middleware;
    }

    private function checkRouteType(string $type): void
    {
        $type = strtoupper($type);
        if(!in_array($type, self::$routesTypes)) {
            Error::renderError("RouteTypeNotValid", $type);
            return;
        }
    }





}