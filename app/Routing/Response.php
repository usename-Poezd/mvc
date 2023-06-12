<?php

namespace App\Routing;
use App\Contracts\Response as Base;

class Response implements Base
{
    public function __construct(
        protected mixed $data,
        protected int $status = 200,
        protected array $options = [
            "Content-Type" => "application/json;charset=utf-8"
        ],
    ){}

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return string
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return string
     * @throws \JsonException
     */
    public function getBody(): string
    {
        if (is_array($this->data)) {
            return json_encode($this->data, JSON_THROW_ON_ERROR);
        }

        return strval($this->data);
    }


}