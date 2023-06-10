<?php

namespace App\Routing;

class Request
{
    public function __construct(
        protected string $host,
        protected string $method,
        protected string $url
    )
    {}

    public static function get(): Request {
        return new Request($_SERVER["HTTP_HOST"], $_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}