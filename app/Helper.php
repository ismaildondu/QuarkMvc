<?php

namespace QuarkMvc\app;


use QuarkMvc\models\IDBModel;

abstract class Helper extends IDBModel
{
    public const NUM_MIN="min";
    public const NUM_MAX="max";
    public const REQUIRED="required";
    public const EMAIL="email";
    public const NUMBER="number";
    public const STRING="string";
    public const INT="int";
    public const FLOAT="float";
    public const BOOL="bool";
    public const MAX_LENGTH="max_length";
    public const MIN_LENGTH="min_length";
    public const EQUAL="equal";
    public const NOT_EQUAL="not_equal";
    public const MATCH="match";
    public const NOT_MATCH="not_match";
    public const PHONE="phone";
    public const URL="url";
    public const IP="ip";
    public const NO_SPECIAL_CHARS="no_special_chars";
    public const DATE="date";
    public const DATETIME="datetime";
    public const SPESIFIC_EMAIL_DOMAIN="spesific_email_domain";
    public const NAME="name";
    public const USERNAME="username";
    private array $errors=[];
    public const RULES_ERROR_MSG=[
        "required"=>"The :attribute field is required",
        "email"=>"The :attribute field is not valid email",
        "number"=>"The :attribute field is not valid number",
        "string"=>"The :attribute field is not valid string",
        "int"=>"The :attribute field is not valid int",
        "float"=>"The :attribute field is not valid float",
        "bool"=>"The :attribute field is not valid bool",
        "max_length"=>"The :attribute field is not valid max length",
        "min_length"=>"The :attribute field is not valid min length",
        "equal"=>"The :attribute field is not valid equal",
        "not_equal"=>"The :attribute field is not valid not equal",
        "match"=>"The :attribute field is not valid match",
        "not_match"=>"The :attribute field is not valid not match",
        "phone"=>"The :attribute field is not valid phone",
        "url"=>"The :attribute field is not valid url",
        "ip"=>"The :attribute field is not valid ip",
        "no_special_chars"=>"The :attribute field is not valid no special chars",
        "date"=>"The :attribute field is not valid date",
        "datetime"=>"The :attribute field is not valid datetime",
        "spesific_email_domain"=>"The :attribute field is not valid spesific email domain",
        "min"=>"The :attribute field is not valid min",
        "max"=>"The :attribute field is not valid max",
        "name"=>"The :attribute field is not valid name",
        "username"=>"The :attribute field is not valid username",
    ];
    private array $customErrorMessage=[];
    public const RULES_KEY_INT=[
        "ip",
        "url",
        "phone",
        "email",
        "required",
        "number",
        "string",
        "int",
        "float",
        "bool",
        "no_special_chars",
        "date",
        "datetime",
        "name",
        "username"
    ];
    public const RULES_KEY_STRING=[
        "max_length",
        "min_length",
        "equal",
        "not_equal",
        "match",
        "not_match",
        "min",
        "max",
        "spesific_email_domain"
    ];


    public function check(array $data,array $rule,array $customErrorMessage=[]):array{
        if(count($customErrorMessage)>0){
            foreach ($customErrorMessage as $key=>$value){
                if(!array_key_exists($key,self::RULES_ERROR_MSG)){
                    Error::renderError("CustomErrorMessageKeyNotValid",$key);
                }else{
                    $this->customErrorMessage[$key]=$value;
                }
            }
        }
        foreach ($data as $key=>$value){
            if(!array_key_exists($key,$rule)){
                Error::renderError("HelperKeyFieldNotValid",$key);
            }else{
                if(!($rule[$key])){
                    Error::renderError("RuleValueMustBeArray",$key);
                }
                foreach($rule[$key] as $ruleKey=>$ruleValue) {
                    if (is_int($ruleKey)) {
                        if (!in_array($ruleValue, self::RULES_KEY_INT)) {
                            Error::renderError("HelperKeyNotValid", $ruleValue);
                        }
                        $ruleSwitch = $ruleValue;
                        $ruleValue = $ruleValue;
                        switch ($ruleSwitch){
                            case self::REQUIRED:
                                if(!$this->required($value)){
                                    $this->addErrorMessages($key,$ruleSwitch);
                                }
                                break;
                            case self::EMAIL:
                                if(!$this->email($value)){
                                    $this->addErrorMessages($key,$ruleSwitch);
                                }
                                break;
                            case self::NUMBER:
                                if(!$this->number($value)){
                                    $this->addErrorMessages($key,$ruleSwitch);
                                }
                                break;
                            case self::STRING:
                                if(!$this->string($value)){
                                    $this->addErrorMessages($key,$ruleSwitch);
                                }
                                break;
                            case self::INT:
                                if(!$this->int($value)){
                                    $this->addErrorMessages($key,$ruleSwitch);
                                }
                                break;
                            case self::FLOAT:
                                if(!$this->float($value)){
                                    $this->addErrorMessages($key,$ruleSwitch);
                                }
                                break;
                            case self::BOOL:
                                if(!$this->bool($value)){
                                    $this->addErrorMessages($key,$ruleSwitch);
                                }
                                break;
                            case self::PHONE:
                                if(!$this->phone($value)){
                                    $this->addErrorMessages($key,$ruleSwitch);
                                }
                                break;
                            case self::URL:
                                if(!$this->url($value)){
                                    $this->addErrorMessages($key,$ruleSwitch);
                                }
                                break;
                            case self::IP:
                                if(!$this->ip($value)){
                                    $this->addErrorMessages($key,$ruleSwitch);
                                }
                                break;
                            case self::NO_SPECIAL_CHARS:
                                if(!$this->no_special_chars($value)){
                                    $this->addErrorMessages($key,$ruleSwitch);
                                }
                                break;
                                case self::DATE:
                                if(!$this->date($value)){
                                    $this->addErrorMessages($key,$ruleSwitch);
                                }
                                break;
                                case self::DATETIME:
                                if(!$this->datetime($value)){
                                    $this->addErrorMessages($key,$ruleSwitch);
                                }
                                break;
                                case self::NAME:
                                if(!$this->name($value)){
                                    $this->addErrorMessages($key,$ruleSwitch);
                                }
                                break;
                                case self::USERNAME:
                                if(!$this->username($value)){
                                    $this->addErrorMessages($key,$ruleSwitch);
                                }
                            default:
                                break;
                        }
                    } else {
                        if (!in_array($ruleKey, self::RULES_KEY_STRING)) {
                            Error::renderError("HelperKeyNotValid", $ruleKey);
                        }
                        $ruleSwitch = $ruleKey;
                        $ruleValue = $ruleValue;

                        switch ($ruleSwitch) {
                            case self::MAX_LENGTH:
                                if (!$this->max_length($value, $ruleValue)) {
                                    $this->addErrorMessages($key,$ruleSwitch);
                                }
                                break;
                            case self::MIN_LENGTH:
                                if (!$this->min_length($value, $ruleValue)) {
                                    $this->addErrorMessages($key,$ruleSwitch);
                                }
                                break;
                            case self::EQUAL:
                                if (!$this->equal($value, $ruleValue)) {
                                    $this->addErrorMessages($key,$ruleSwitch);
                                }
                                break;
                            case self::NOT_EQUAL:
                                if (!$this->not_equal($value, $ruleValue)) {
                                    $this->addErrorMessages($key,$ruleSwitch);
                                }
                                break;
                            case self::MATCH:
                                if (!$this->match($value, $ruleValue)) {
                                    $this->addErrorMessages($key,$ruleSwitch);
                                }
                                break;
                            case self::NOT_MATCH:
                                if (!$this->not_match($value, $ruleValue)) {
                                    $this->addErrorMessages($key,$ruleSwitch);
                                }
                                break;
                            case self::NUM_MIN:
                                if (!$this->min($value, $ruleValue) || !is_numeric($value)) {
                                    $this->addErrorMessages($key,$ruleSwitch);
                                }
                                break;
                            case self::NUM_MAX:
                                if (!$this->max($value, $ruleValue) || !is_numeric($value)) {
                                    $this->addErrorMessages($key,$ruleSwitch);
                                }
                                break;
                                case self::SPESIFIC_EMAIL_DOMAIN:
                                if (!$this->spesific_email_domain($value, $ruleValue)) {
                                   $this->addErrorMessages($key,$ruleSwitch);
                                }
                                break;
                            default:
                                break;
                        }

                    }
                }
                }
            }
        return $this->errors;


    }

    private function addErrorMessages(string $key,string $ruleKey):void{
        $error="";
        if(array_key_exists($ruleKey,$this->customErrorMessage)){
            $error=$this->customErrorMessage[$ruleKey];
        }else{
            if(str_contains(self::RULES_ERROR_MSG[$ruleKey],":attribute")) {
                $error=str_replace(":attribute",$key,self::RULES_ERROR_MSG[$ruleKey]);
            }else{
                $error = self::RULES_ERROR_MSG[$ruleKey];
            }
        }
        $this->errors[$key][]=$error;
    }
    public function required($value):bool{
        if(is_array($value)){
            return !empty($value);
        }else{
            return isset($value) && $value !== "";
        }
    }
    public function email($value):bool{
        return filter_var($value,FILTER_VALIDATE_EMAIL);
    }
    public function number($value):bool{
        return is_numeric($value);
    }
    public function string($value):bool{
        return is_string($value);
    }
    public function name($value):bool{
        return preg_match('/^[\p{L}]+$/u', $value);
    }
    public function int($value):bool{
        return is_int($value);
    }
    public function float($value):bool{
        return is_float($value);
    }
    public function bool($value):bool{
        return is_bool($value);
    }
    public function max_length($value,$max):bool{
        return strlen($value)<=$max;
    }
    public function min_length($value,$min):bool{
        return strlen($value)>=$min;
    }
    public function equal($value,$equal):bool{
        return $value===$equal;
    }
    public function not_equal($value,$not_equal):bool{
        return $value!==$not_equal;
    }
    public function match($value,$match):bool{
        return preg_match($match,$value);
    }
    public function not_match($value,$not_match):bool{
        return !preg_match($not_match,$value);
    }
    public function phone($value):bool{
        return preg_match("/^([0-9\s\-\+\(\)]*)$/",$value);
    }
    public function url($value):bool{
        return filter_var($value,FILTER_VALIDATE_URL);
    }
    public function ip($value):bool{
        return filter_var($value,FILTER_VALIDATE_IP);
    }
    public function min($value,$min):bool{
        return $value>=$min;
    }
    public function max($value,$max):bool{
        return $value<=$max;
    }
    public function no_special_chars($value):bool{
        return preg_match("/^([\p{L}\p{N}]+)$/u", $value);
    }
    public function date($value):bool{
        return preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/",$value);
    }
    public function datetime($value):bool{
        return preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2})\:([0-9]{2})$/",$value);
    }
    public function spesific_email_domain($value,$domain):bool{
        if(!is_array($domain)){
            $domain=[$domain];
        }
        foreach ($domain as $item){
            if(preg_match("/^([a-zA-Z0-9\-_]+)@($item)$/",$value)){
                return true;
            }else{
                return false;
            }
        }
    }
    public function username($value):bool{
        return preg_match("/^([a-zA-Z0-9\_]+)$/",$value);
    }



}