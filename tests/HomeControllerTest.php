<?php
namespace App\Test;

use App\Test\ApiTestCase;

class HomeControllerTest extends ApiTestCase
{
    public function testIndex()
    {
        $response = self::$client->get('/');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('there will be dragons...', $response->getBody()->getContents());
    }
}
