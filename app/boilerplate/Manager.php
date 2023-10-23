<?php

namespace QuarkMvc\app\boilerplate;

class Manager
{
    private const EXTENSION = ".boilerplate";
    public function getBoilerplate(string $name,array $args=[]): string{
        if(!file_exists("app/boilerplate/".$name.self::EXTENSION)){
            return "";
        }
        $name=ucfirst($name);
        $name = $name . self::EXTENSION;
        $boilerplate = file_get_contents("app/boilerplate/".$name);
        if(count($args)!=0){
            foreach ($args as $key => $value) {
                $boilerplate = str_replace("@".$key."@", $value, $boilerplate);
            }
        }
        return $boilerplate;
    }
    public static function getIndexFile():string{
        return file_get_contents("public/index.php");
    }
    public static function overwriteIndexFile(string $content):void{
        file_put_contents("public/index.php",$content);
    }



}