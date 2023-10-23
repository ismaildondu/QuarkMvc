# QuarkMVC
QuarkMVC is a simple MVC framework for PHP. Firstly it was created for my own school project, but now it's available for everyone.
It's not a perfect framework, but it's simple and easy to use for my school project.
## Features
- Simple and easy to use
- Easy to create new controllers
- Twig template engine
- Validation helper
- Error management
- Routing
- Middleware

## Installation
1. Clone the repository
2. Run `composer install`
3. Run `composer dump-autoload -o`
4. Go to public folder and run `php -S localhost:8000` or use your own server

## Usage Documentation

### Controllers
Controllers are located in `app/controllers` folder. If you use this framework and you want to create a new controller, 2 ways are available:
1. RECOMMENDED: Use the command `php app/commands/bin.php create:controller <controller_name>`. This method automatically creates a new controller and new view. Also, it adds a new route to `public/index.php` file.
2. NOT RECOMMENDED:
    - Create a new file in `app/controllers` folder ex. `TestController.php`
    - Create a new class in this file ex. `class TestController extends IController`
    - Override methods from `IController` class
    - Create a new file in `views` folder ex. `test.twig`
    - Go to public/index.php and add a new route ex. `$quark->routes->setRoute('test', 'TestController',"get");`

Controller lifecycle:
1. The controller is called from the router
2. The controller before() method is called
3. The controller index() method is called
4. The controller after() method is called

Sample controller:
```php
<?php
namespace QuarkMvc\controllers;
use QuarkMvc\app\Csrf;
use QuarkMvc\app\Render;
use QuarkMvc\app\SecurityHelper;
class HomeController implements IController
{
    public function index(array $params): void
    {
        $csrf=SecurityHelper::generateToken();
        $this->render('home',["params"=>$csrf]);
    }
    public function render(string $view, array $params = [],int $statusCode=200): void
    {
        Render::render($view, $params,$statusCode);
    }

    public function before(array $params): void
    {

    }
    public function after(array $params): void
    {

    }
}
```
     *  index,before,after $params["PATH"] +
     *  index,before,after $params["GET"][] +
     *  index,before,after $params["PARAMS"][] + (From route like /user/:param)
     *  index,before,after $params["POST"][] +
     *  index,before,after $params["COOKIES"][] +
     *  index,before,after $params["FILES"][] +
     *  index,before,after $params["SERVER"][] +
     *  index,before,after $params["REQUEST"][] +

### Views
Views are located in `app/views` folder. Views are rendered by `Render::render()` method. This method takes 3 parameters:
- View name
- Parameters
- Status code

This frameworks uses Twig template engine. You can read more about Twig [here](https://twig.symfony.com/).
 - Render::render() must to be used in controller render() method.
 - Parameters must be an array also Parameters are optional.
 - Parameters => twig variables
 - Simple example: `$params["name"] = "John";` => `{{ name }}` in twig file

### Routing
Routing is a variable in Quark class. 
- Simple routing:
```php
$quark = new Quark();
$quark->routes->setRoute('register', 'RegisterController',"get");
$quark->routes->setRoute('register', 'RegisterController',"post");
```
- Routing with parameters:
```php
$quark = new Quark();
$quark->routes->setRoute('user/:params', 'UserFetchController',"get");
```
- Route parameters are available in controller in `$params["PARAMS"][]` array.
Route types:
- get
- post
- put
- delete
- patch
- options
- head
- any

Route setRoute method 3rd parameter is not case sensitive:
```php
$quark = new Quark();
$quark->routes->setRoute('register', 'RegisterController',"gEt"); //valid
$quark->routes->setRoute('register', 'RegisterController',"pOst"); //valid
$quark->routes->setRoute('register', 'RegisterController',"poost"); // invalid
```

### Middleware
Middleware are located in `app/middleware` folder. If you use this framework and you want to create a new middleware:
1. Go to `app/middleware` folder
2. Create a new file ex. `TestMiddleware.php` and implement `IMiddleware` interface
3. Go to `public/index.php` and add a new middleware ex. `$quark->routes->addMiddleware('home', 'DemoMiddleware',"get");`

Middleware lifecycle:
1. before() method is called
2. handle() method is called
3. after() method is called
4. Router calls the controller

### 404 Error
If you want to create a custom 404 error page:
1. Create 404 controller in `app/controllers` folder
2. go to index.php and add a new route `$quark->routes->set404Route('Custom404Controller');` (Custom404Controller is the name of the controller)

### Validation Helper
Validation helper is located in `app/Helper.php` file. If you want Helper you must to your model class extend `Helper` class.
Simple example:
```php
class UserModel extends Helper
{
    private array $data;
    private array $rule;
    private array $customErrorMessage=[];
    public function __construct(string $username,string $name,string $surname,string $email,string $password,string $passwordConfirm)
    {
        $this->data=[
            "username"=>$username,
            "name"=>$name,
            "surname"=>$surname,
            "email"=>$email,
            "password"=>$password,
            "passwordConfirm"=>$passwordConfirm
        ];
        $this->rule=[
            "username"=>[
                self::REQUIRED,
                self::MIN_LENGTH=>3,
                self::MAX_LENGTH=>20,
                self::USERNAME
            ],
            "name"=>[
                self::REQUIRED,
                self::MIN_LENGTH=>3,
                self::MAX_LENGTH=>20,
                self::NAME
            ],
            "surname"=>[
                self::REQUIRED,
                self::MIN_LENGTH=>3,
                self::MAX_LENGTH=>20,
                self::NAME
            ],
            "email"=>[
                self::REQUIRED,
                self::EMAIL
            ],
            "password"=>[
                self::REQUIRED,
                self::MIN_LENGTH=>6,
                self::MAX_LENGTH=>20
            ],
            "passwordConfirm"=>[
                self::REQUIRED,
                self::EQUAL=>$this->data["password"]
            ]

        ];
        $this->customErrorMessage=[
           self::NUMBER=>"Bu alan sadece sayılardan oluşabilir",
           self::REQUIRED=>"Bu alan boş bırakılamaz",
        ];
    }
    public function checkRule():array
    {
        $errors=$this->check($this->data,$this->rule,$this->customErrorMessage);
        if(count($errors)>0){
            return $errors;
        }
        return [];
    }
}
```
- $data is the data that will be validated.
- $rule is the validation rules.
- $customErrorMessage is the custom error messages. If you don't want to use custom error messages, you can delete this variable.
- $this->check() method from Helper class returns an array of errors. If there are no errors, it returns an empty array.

Validation rules:
- REQUIRED => Checks if the value is empty or not. `[REQUIRED]`
- EMAIL => Checks if the value is a valid email or not. `[EMAIL]`
- NUMBER => Checks if the value is a valid number or not. `[NUMBER]`
- STRING => Checks if the value is a valid string or not. `[STRING]`
- INT => Checks if the value is a valid int or not. `[INT]`
- FLOAT => Checks if the value is a valid float or not. `[FLOAT]`
- BOOL => Checks if the value is a valid bool or not. `[BOOL]`
- MAX_LENGTH => Checks if the value is a valid max length or not. `[MAX_LENGTH=>10]`
- MIN_LENGTH => Checks if the value is a valid min length or not. `[MIN_LENGTH=>10]`
- EQUAL => Checks if the value is equal to the given value or not. `[EQUAL=>"test"]`
- NOT_EQUAL => Checks if the value is not equal to the given value or not. `[NOT_EQUAL=>"test"]`
- MATCH => Checks if the value is match to the given value or not. `[MATCH=>"/^[a-zA-Z0-9]+$/"]`
- NOT_MATCH => Checks if the value is not match to the given value or not. `[NOT_MATCH=>"/^[a-zA-Z0-9]+$/"]`
- PHONE => Checks if the value is a valid phone number or not. `[PHONE]`
- URL => Checks if the value is a valid url or not. `[URL]`
- IP => Checks if the value is a valid ip or not. `[IP]`
- NO_SPECIAL_CHARS => Checks if the value is a valid no special chars or not. `[NO_SPECIAL_CHARS]`
- DATE => Checks if the value is a valid date or not. `[DATE]`
- DATETIME => Checks if the value is a valid datetime or not. `[DATETIME]`
- SPESIFIC_EMAIL_DOMAIN => Checks if the value is a valid spesific email domain or not. `[SPESIFIC_EMAIL_DOMAIN=>["gmail.com","hotmail.com"]]`


`$this->check()` simple return array;
```php
        $this->data=[
            "username"=>$username,
            "email"=>$email,
        ];
        $this->rule=[
            "username"=>[
                self::REQUIRED,
                self::MIN_LENGTH=>3,
                self::MAX_LENGTH=>20,
                self::USERNAME
            ],
            "email"=>[
                self::REQUIRED,
                self::EMAIL
            ]
        ]
        $errors=$this->check($this->data,$this->rule);
        echo(json_encode($errors));
```
If there are no errors, it returns an empty array.
```php
[]
```
But if there are errors, it returns an array of errors.
```php
[
  ["username"]=>[
    "This field is required",
    "This field must be at least 3 characters",
  ],
    ["email"]=>[
        "This field is required",
        "This field must be a valid email"
    ]
]
```
        
If you want to use custom error messages, you can use check() method like this:
```php
        $this->customErrorMessage=[
           self::NUMBER=>"Αυτό το πεδίο μπορεί να περιέχει μόνο αριθμούς",
           self::REQUIRED=>"该字段只能包含数字",
        ];
        $errors=$this->check($this->data,$this->rule,$this->customErrorMessage);
```

You can download and use the project to go deeper into what you need to know. Remember, this framework was made only for use in my school projects.

----
* License: MIT
* Author:  İsmail Döndü