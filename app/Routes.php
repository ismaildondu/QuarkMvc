<?php

namespace QuarkMvc\app;
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
        $route=$this->routeSetRule($route);
        $route=str_replace(':params','([a-zA-Z0-9\-_]+)',$route);
        $this->routes[$type][$route] = $controller;
    }

    public function set404Route(string $controller): void
    {
        $this->routes['404'] = $controller;
    }
    public function executeRoute($isSearch = false): void
    {
        $matchedRoute = $this->getMatchedRoute();
        if($matchedRoute){
            $this->executeController($matchedRoute["callBack"],$this->renderArray($matchedRoute["params"]));
            return;
        }else{
            $this->handle404();
        }
    }
    private function getMatchedRoute():?array{
        $path = $this->stringEndsWithPath($this->request->currentPath());
        $method = $this->request->method();
        $path = $this->stringEndsWithPath($path);

        foreach($this->routes[$method] as $route=>$callBack){
            if(preg_match("@^$route$@",$path,$matches)){
                return [
                    "route"=>$route,
                    "callBack"=>$callBack,
                    "params"=>array_slice($matches, 1)
                ];
            }
        }
        return null;
    }
    private function handle404(): void
    {
        if(isset($this->routes[404])){
            $this->executeController($this->routes[404],$this->renderArray());
        }else{
            Error::renderError("Default404Error");
        }
    }
    // ref: routeSetRule() in app/Routes.php for stringEndsWithPath()
    private function stringEndsWithPath(string $path): string
    {
        if(str_ends_with($path, "/")) {
            $path = substr($path, 0, -1);
        }
        return $path;
    }
    private function searchRoute($path,$method):bool{
        $path=$this->stringEndsWithPath($path);
        foreach($this->routes[$method] as $route=>$callBack){
            if(preg_match("@^$route$@",$path,$matches)){
                return true;
            }
        }
        return false;
    }

    private function renderArray(array $params=[]): array
    {
        $return=[
            "PATH"=>$this->request->currentPath(),
            "PARAMS"=>$params,
        ];
        $return["METHOD"]=$this->request->method();
        $return["GET"]=$_GET;
        $return["POST"]=$_POST;
        $return["COOKIES"]=$_COOKIE;
        $return["FILES"]=$_FILES;
        $return["SERVER"]=$_SERVER;
        $return["REQUEST"]=$_REQUEST;
        $return["IS_ROUTE_FOUND"]=$this->searchRoute($return["PATH"],$return["METHOD"]);
        if(Quark::$isDebug){
            Render::render("default/debugger", [
                "activePath" => $return["PATH"],
                "activeMethod" => $return["METHOD"],
                "isPathFound" => $return["IS_ROUTE_FOUND"],
                "params" => json_encode($return["PARAMS"], JSON_PRETTY_PRINT),
                "get" => json_encode($return["GET"], JSON_PRETTY_PRINT),
                "post" => json_encode($return["POST"], JSON_PRETTY_PRINT),
            ], 404);
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
            if(preg_match("@^$route$@",$params["PATH"])){
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
        $route=$this->routeSetRule($route);
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
    private function routeSetRule(string $route): string
    {
        if($route=="/"){
            return "";
        }
        if ($route!="" && $route[0] !== '/') {
            return '/' . $route;
        }
        return $route;
    }
}