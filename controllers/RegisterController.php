<?php

namespace QuarkMvc\controllers;
use QuarkMvc\app\Request;
use QuarkMvc\app\SecurityHelper;
class RegisterController implements IController
{

    public function index(array $params): void
    {
        if(isset($_POST["token"])){
            if(!SecurityHelper::checkToken($_POST["token"])){
                $this->render("register",["token"=>SecurityHelper::generateToken()],400);
            }
        }
        $csrf=SecurityHelper::generateToken();
        if($_POST){
            $user=new \QuarkMvc\models\UserModel($_POST['username'],$_POST['name'],$_POST['surname'],$_POST['email'],$_POST['password'],$_POST['password2']);
            $errors=$user->checkRule();
            if(count($errors)>0){
                $postCache=$_POST;
                unset($postCache["password"]);
                unset($postCache["password2"]);
                $this->render('register',["error"=>$errors,"token"=>$csrf,"post"=>$postCache]);
            }else{
                $this->render('register',["error"=>null,"token"=>$csrf]);
            }
        }else{
            $this->render('register',["token"=>$csrf]);
        }


    }
    public function render(string $view, array $params = [],int $statusCode=200): void
    {
        \QuarkMvc\app\Render::render($view, $params,$statusCode);
        die();
    }

    public function before(array $params): void
    {

    }
    public function after(array $params): void
    {

    }
}