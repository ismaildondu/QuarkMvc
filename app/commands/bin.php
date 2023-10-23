<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use QuarkMvc\app\commands\Manager;

if(isset($argv[1])) {
    $command = $argv[1];
    if(count($argv)>2 && isset($argv[2])){
        $args = array_slice($argv, 2);
        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }
    }else{
        $args = [];
    }
    $isHelp = false;
    if(isset($args[0]) && $args[0] == "help"){
        $isHelp = true;
    }
    $manager = new Manager($args,$command,$isHelp);
}
