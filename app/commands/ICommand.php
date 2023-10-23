<?php

namespace QuarkMvc\app\commands;

interface ICommand
{
    public function execute(array $args): void;
    public function help(): void;

}