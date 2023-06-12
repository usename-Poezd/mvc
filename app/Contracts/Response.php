<?php

namespace App\Contracts;

interface Response
{
    public function getBody(): string;
    public function getStatus(): int;
    public function getOptions(): array;
}