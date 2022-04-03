<?php

namespace App\Test;

class CustomerControllerTest extends ApiTestCase
{
    private static array $tempCustomers = [];

    /**
     * @dataProvider customerProvider
     */
    public function testCreate(array $customer)
    {
        $response = self::$client->post('/api/customers', [
            'json' => $customer,
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $customerResponse = $this->getJsonData($response);
        self::$tempCustomers[] = $customerResponse;

        $this->assertArrayHasKey('id', $customerResponse);
        $this->assertArrayHasKey('name', $customerResponse);
        $this->assertContains($customer['name'], $customerResponse);
    }

    public function testList()
    {
        $response = self::$client->get('/api/customers');
        $this->assertEquals(200, $response->getStatusCode());

        $customerList = $this->getJsonData($response);

        foreach (self::$tempCustomers as $customer) {
            $this->assertContains($customer, $customerList);
        }
    }

    public function testGet()
    {
        foreach (self::$tempCustomers as $tempCustomer) {
            $response = self::$client->get('/api/customers/' . $tempCustomer['id']);
            $this->assertEquals(200, $response->getStatusCode());

            $customer = $this->getJsonData($response);
            $this->assertEquals($tempCustomer, $customer);

        }
    }

    public function testDelete()
    {
        foreach (self::$tempCustomers as $tempCustomer) {
            $response = self::$client->delete('/api/customers/' . $tempCustomer['id']);
            $this->assertEquals(204, $response->getStatusCode());
        }
    }

    public function customerProvider()
    {
        return [
            [
                ['name' => 'Tim Berners-Lee'],
            ],
            [
                ['name' => 'Donald Ervin Knuth'],
            ]
        ];
    }
}
