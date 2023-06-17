<?php

namespace App\Contracts;

interface Route
{
    // Get route path
    public function getPath(): string;
    // Get route method
    public function getMethod(): string;

    // Get regex for match url
    public function getRegex(): string;
    public function getArguments(): array;

    // Get all stuff for controller
    public function getController(): string;
    public function getAction(): string;
}