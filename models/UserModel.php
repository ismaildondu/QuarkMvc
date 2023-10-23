<?php

namespace QuarkMvc\models;

use QuarkMvc\app\Helper;

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