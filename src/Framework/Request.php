<?php

namespace App\Framework;

class Request
{
    protected string $path;
    protected string $method;
    protected array $queryParams = [];
    protected array $requestParams = [];
    private string $dynamicParameter;
    protected string $body;
    protected string $contentType;

    private function __construct()
    {
    }

    public static function initialize(): Request
    {
        $request = new static();
        $request->path = $_SERVER['PHP_SELF'];
        $request->method = $_SERVER['REQUEST_METHOD'];
        $request->queryParams = $_GET;
        $request->requestParams = $_POST;
        $request->body = file_get_contents('php://input');
        $request->contentType = $_SERVER['CONTENT_TYPE'] ?? 'text/html';

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
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    /**
     * @return array
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getQueryParam(string $key): ?string
    {
        return $this->queryParams[$key] ?? null;
    }

    public function getRequestParams(): array
    {
        return $this->requestParams;
    }

    public function getDataParam(string $key): ?string
    {
        return $this->requestParams[$key] ?? null;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getJsonData(): ?array
    {
        if (!substr($this->contentType, -5) === 'json') {
            throw new \Exception('Content-Type is not json');
        }

        return json_decode($this->body, true);

    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function getDynamicParameter(): string
    {
        return $this->dynamicParameter;
    }

    public function setDynamicParameter(string $dynamicParameter): void
    {
        $this->dynamicParameter = $dynamicParameter;
    }


}
