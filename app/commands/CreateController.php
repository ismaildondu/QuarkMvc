<?php

namespace QuarkMvc\app\commands;
use QuarkMvc\app\Helper;
use QuarkMvc\app\Quark;
use QuarkMvc\app\boilerplate\Manager;

class CreateController extends Command
{
    public function __construct()
    {
        $this->commandName = "create:controller";
        $this->commandDescription = "Create a new controller";
    }

    public function execute(array $args): void
    {
        $controllerName = $args[0];
        $controllerName = ucfirst($controllerName);
        $controllerName = str_replace("Controller", "", $controllerName);
        $controllerName = $controllerName . "Controller";
        $controllerName = ucfirst($controllerName);
        $dir="controllers";
        if(!file_exists($dir)){
            mkdir($dir);
        }else{
            if(file_exists($dir."/".$controllerName.".php")){
                echo "Controller already exists\n";
                return;
            }else{
                $boilerplate = new Manager();

                $controllerView = str_replace("Controller", "", $controllerName);
                $controllerView = strtolower($controllerView);
                $controllerView = $controllerView ."-". rand(0, 1000);
                $boilerplateController=$boilerplate->getBoilerplate("Controller",
                    [
                        "controller_name"=>$controllerName,
                        "controller_view"=>strtolower($controllerView),
                    ]
                );
                $boilerplateView=$boilerplate->getBoilerplate("DefaultView");
                try{
                    file_put_contents($dir."/".$controllerName.".php",$boilerplateController);
                    file_put_contents("views/".$controllerView.".twig",$boilerplateView);
                    $indexFile=Manager::getIndexFile();
                    if(strpos($indexFile, '$quark->run();') === false){
                        echo "Error creating controller\n";
                        return;
                    }
                    $indexFile=str_replace('$quark->run();', '$quark->routes->setRoute("'.$controllerView.'", "'.$controllerName.'","get");'."\n".'$quark->run();', $indexFile);
                    Manager::overwriteIndexFile($indexFile);
                    echo "Controller created successfully\n";
                    echo "Vist /".$controllerView."\n";
                    return;
                }catch (\Exception $e){
                    echo "Error creating controller\n";
                    return;
                }
            }
        }
    }

    public function help(): void
    {
       echo "create:controller <controller_name>";
    }
}