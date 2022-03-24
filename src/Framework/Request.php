<?php

namespace App\Framework;

class Request
{
    protected string $path;
    protected string $method;
    protected array $queryParams = [];

    private function __construct(){}

    public static function initialize(): Request{

        $request = new static();
        $request->path = $_SERVER['PHP_SELF'];
        $request->method = $_SERVER['REQUEST_METHOD'];
        $request->queryParams = $_GET;
        return $request;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getQueryParam(string $key)
    {
        return $_GET[$key] ?? null;
    }





}
