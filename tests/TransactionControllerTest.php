<?php

namespace App\Test;

use App\Entity\Transaction;
use App\Service\TransactionManager;
use App\Service\AccountManager;

class TransactionControllerTest extends ApiTestCase
{
    private static array $tempTransactions = [];

    public function testList()
    {
        $response = self::$client->get('/api/transactions');
        $this->assertEquals(200, $response->getStatusCode());

        $transactionManager = new TransactionManager();
        $dbTransactions= $transactionManager->getAll();

        $responseTransactionList = $this->getJsonData($response);

        foreach ($dbTransactions as $transaction) {
            $this->assertContains($transaction, $responseTransactionList);
        }
    }

    /**
     * @dataProvider transactionProvider
     */
    public function testCreate(array $transaction)
    {
        $accountManager = new AccountManager();
        $allCustomers = $accountManager->getAll();
        $sourceAccount = $allCustomers[array_rand($allCustomers)];
        $destinationAccount = $allCustomers[array_rand($allCustomers)];

        $transaction['sourceAccountId'] = $sourceAccount['id'];
        $transaction['destinationAccountId'] = $destinationAccount['id'];

        $response = self::$client->post('/api/transactions', [
            'json' => $transaction
        ]);

        $transactionResponse = $this->getJsonData($response);

        if ($transaction['amount'] <= 0 || $sourceAccount['id'] === $destinationAccount['id'] || $sourceAccount['balance'] < $transaction['amount']) {
            $this->assertEquals(400, $response->getStatusCode());
            $this->assertArrayHasKey('error', $transactionResponse);
        } else {
            $this->assertEquals(200, $response->getStatusCode());
            self::$tempTransactions[] = $transactionResponse;
            $this->assertArrayHasKey('id', $transactionResponse);
            $this->assertArrayHasKey('sourceAccountId', $transactionResponse);
            $this->assertArrayHasKey('destinationAccountId', $transactionResponse);
            $this->assertArrayHasKey('amount', $transactionResponse);

            $this->assertContains($transaction['sourceAccountId'], $transactionResponse);
            $this->assertContains($transaction['destinationAccountId'], $transactionResponse);
            $this->assertContains($transaction['amount'], $transactionResponse);
        }
    }

    public function testGet()
    {
        foreach (self::$tempTransactions as $transaction) {
            $response = self::$client->get('/api/transactions/' . $transaction['id']);
            $this->assertEquals(200, $response->getStatusCode());
            $transactionResponse = $this->getJsonData($response);
            $this->assertEquals($transaction, $transactionResponse);
        }
    }

    public function testDelete()
    {
        $transactionManager = new TransactionManager();

        foreach (self::$tempTransactions as $tempCustomer) {
            $response = $transactionManager->getOrm()->delete(Transaction::class, 'id', $tempCustomer['id']);
            $this->assertEquals(1, $response);
        }
    }

    public function transactionProvider()
    {
        return [
            [
                ['amount' => 0],
            ],
            [
                ['amount' => 5],
            ],
            [
                ['amount' => 1000],
            ],
            [
                ['amount' => -55],
            ]
        ];
    }
}
