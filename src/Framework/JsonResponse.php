<?php

namespace App\Framework;

class JsonResponse extends Response
{
    public function __construct(?array $content = [], int $statusCode = 200, array $headers = [], array $cookies = [])
    {
        $headers['Content-Type'] = ['application/json; charset=utf-8'];

        if (is_array($content)) {
            $jsonContent = json_encode($content);
        }else{
            $jsonContent = '';
        }

        parent::__construct($jsonContent, $statusCode, $headers, $cookies);
    }

    public function setHeaders(array $headers): void
    {
        $headers['Content-Type'] = ' application/json; charset=utf-8';
        $this->headers = $headers;
    }



}
