<?php

namespace App\Test;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class ApiTestCase extends TestCase
{
    protected static Client $client;

    public static function setUpBeforeClass(): void
    {
        self::$client = new Client([
                'base_uri' => 'http://localhost:8000',
                'timeout' => 2.0,
                'http_errors' => false,
            ]
        );
    }

    public function getJsonData(ResponseInterface $response): array
    {
        $content = $response->getBody()->getContents();
        $this->assertJson($content);

        return json_decode($content, true);
    }

}
