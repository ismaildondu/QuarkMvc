<?php

namespace QuarkMvc\app\commands;

abstract class Command implements ICommand
{
    public string $commandName;
    public string $commandDescription;

    public function execute(array $args): void
    {
    }

    public function help(): void
    {
    }
}