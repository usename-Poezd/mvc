<?php

namespace App\Routing;

class Request
{
    protected string $url = "";
    protected array $query = [];

    protected array $params = [];

    public function __construct(
        protected string $host,
        protected string $method,
        protected string $uri
    )
    {
        $this->url = explode("?", $uri)[0];

        // Parse query string
        $query = [];
        parse_str($_SERVER["QUERY_STRING"], $query);
        $this->query = $query;
    }

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

    /**
     * @param array $params
     * @return Request
     */
    public function setParams(array $params): Request
    {
        $this->params = $params;
        return $this;
    }


    /**
     * @param array $params
     * @return Request
     */
    public function getParam(string $key): ?string
    {
        return $this->params[$key] ?? null;
    }

    /**
     * @return array
     */
    public function getQuery(string $key): mixed
    {
        return $this->query[$key] ?? null;
    }
}