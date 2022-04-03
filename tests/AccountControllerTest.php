<?php

namespace App\Test;

use App\Service\AccountManager;
use App\Service\CustomerManager;

class AccountControllerTest extends ApiTestCase
{
    private static array $tempAccounts = [];

    public function testList()
    {
        $response = self::$client->get('/api/accounts');
        $this->assertEquals(200, $response->getStatusCode());

        $accountManager = new AccountManager();
        $dbAccounts = $accountManager->getAll();

        $responseAccountList = $this->getJsonData($response);

        foreach ($dbAccounts as $account) {
            $this->assertContains($account, $responseAccountList);
        }
    }

    /**
     * @dataProvider accountProvider
     */
    public function testCreate(array $account)
    {
        $customerManager = new CustomerManager();
        $allCustomers = $customerManager->getAll();
        $customer = $allCustomers[array_rand($allCustomers)];

        $account['customerId'] = $customer['id'];
        $response = self::$client->post('/api/accounts', [
            'json' => $account
        ]);

        $accountResponse = $this->getJsonData($response);

        if ($account['balance'] < 0) {
            $this->assertEquals(400, $response->getStatusCode());
            $this->assertArrayHasKey('error', $accountResponse);
        } else {
            $this->assertEquals(200, $response->getStatusCode());
            self::$tempAccounts[] = $accountResponse;
            $this->assertArrayHasKey('id', $accountResponse);
            $this->assertArrayHasKey('customerId', $accountResponse);
            $this->assertArrayHasKey('balance', $accountResponse);

            $this->assertContains($account['customerId'], $accountResponse);
            $this->assertContains($account['balance'], $accountResponse);
        }
    }

    public function testGet()
    {
        foreach (self::$tempAccounts as $account) {
            $response = self::$client->get('/api/accounts/' . $account['id']);
            $this->assertEquals(200, $response->getStatusCode());
            $accountResponse = $this->getJsonData($response);
            $this->assertEquals($account, $accountResponse);
        }
    }

    public function testUpdate()
    {
        foreach (self::$tempAccounts as $account) {
            $account['balance'] = $account['balance'] + rand(1, 100);
            $response = self::$client->patch('/api/accounts/' . $account['id'], [
                'json' => $account
            ]);
            $this->assertEquals(200, $response->getStatusCode());
            $accountResponse = $this->getJsonData($response);
            $this->assertEquals($account['balance'] , $accountResponse['balance']);
        }
    }

    public function testDelete()
    {
        foreach (self::$tempAccounts as $tempCustomer) {
            $response = self::$client->delete('/api/accounts/' . $tempCustomer['id']);
            $this->assertEquals(204, $response->getStatusCode());
        }
    }

    public function accountProvider()
    {
        return [
            [
                ['balance' => 0],
            ],
            [
                ['balance' => 10],
            ],
            [
                ['balance' => 33],
            ],
            [
                ['balance' => -55],
            ]
        ];
    }
}
