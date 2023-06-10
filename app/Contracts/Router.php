<?php
namespace App\Contracts;

interface Router
{
    public static function get(string $route, array $action);
    public static function post(string $route, array $action);
}