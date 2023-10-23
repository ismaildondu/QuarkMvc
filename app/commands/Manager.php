<?php

namespace QuarkMvc\app\commands;


class Manager
{
    private array $commands = [
        "create:controller" => "QuarkMvc\app\commands\CreateController",
    ];

    public function __construct(array $args,string $command,bool $isHelp)
    {
            $this->executeCommand($args,$command,$isHelp);

    }


    private function executeCommand(array $args,string $command,bool $isHelp): void
    {
        if (array_key_exists($command, $this->commands)) {
            $command = $this->commands[$command];
            try{
                $command = new $command();
            }catch (\Exception $e){
                echo "Command not found\n";
                return;
            }

            if($isHelp){
                $command->help();
                return;
            }
            $command->execute($args);
        } else {
            echo "Command not found\n";
        }
    }






}