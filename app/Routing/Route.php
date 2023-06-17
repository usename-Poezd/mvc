<?php

namespace App\Routing;

class Route implements \App\Contracts\Route
{
    // pattern for route arguments
    protected const ARGUMENT_PATTER = '/{(\w+)}/m';

    public function __construct(
        protected string $method,
        protected string $path,
        protected string $controller,
        protected string $action,
    )
    {
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getRegex(): string
    {

        $regex = preg_replace(self::ARGUMENT_PATTER, "(\w+)", $this->path);
        return "/^" . str_replace("/", "\/", $regex) . "$/m";
    }

    public function getArguments(): array
    {
        $argument_names = [];
        preg_match_all(self::ARGUMENT_PATTER, $this->path, $argument_names, PREG_PATTERN_ORDER, 0);

        return $argument_names[1];
    }

    public function getController(): string
    {
       return $this->controller;
    }

    public function getAction(): string
    {
        return $this->action;
    }
}