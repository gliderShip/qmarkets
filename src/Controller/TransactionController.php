<?php

namespace App\Controller;

use App\Exceptions\ClientException;
use App\Framework\JsonResponse;
use App\Framework\Request;
use App\Framework\Response;
use App\Service\AccountManager;
use App\Service\TransactionManager;

class TransactionController
{
    private TransactionManager $transactionManager;
    private AccountManager $accountManager;

    public function __construct()
    {
        $this->transactionManager = new TransactionManager();
        $this->accountManager = new AccountManager();
    }

    public function get(Request $request): Response
    {
        $transactionId = $request->getDynamicParameter();

        if (null === $transactionId) {
            return new JsonResponse(['error' => 'Transaction id is missing'], 400);
        }

        $transaction = $this->transactionManager->getEntity($transactionId);
        if (null === $transaction) {
            return new JsonResponse(['error' => "Transaction ->:$transactionId not found!"], 404);
        }

        $transactionResponse = $this->transactionManager->normalize($transaction);
        return new JsonResponse($transactionResponse);
    }

    public function getAccountTransactions(Request $request): Response
    {
        $accountId = $request->getDynamicParameter();

        if (null === $accountId) {
            return new JsonResponse(['error' => 'Account id is missing'], 400);
        }

        $account = $this->accountManager->getEntity($accountId);
        if (null === $account) {
            return new JsonResponse(['error' => "Account ->:$accountId not found!"], 404);
        }

        $transactions = $this->transactionManager->getAccountTransactions($account);

        return new JsonResponse($transactions);
    }

    public function list(): Response
    {
        $transactions = $this->transactionManager->getAll();

        return new JsonResponse($transactions);
    }

    public function create(Request $request): Response
    {
        $transactionRequest = $request->getJsonData();

        if ($transactionRequest === null) {
            return new JsonResponse(['error' => 'Invalid request'], 400);
        }

        try {
            $transactionEntity = $this->transactionManager->denormalize($transactionRequest);
            $errors = $transactionEntity->getInvalidProperties();
            if (!empty($errors)) {
                return new JsonResponse($errors, 400);
            }

            $transactionEntity = $this->transactionManager->createEntity($transactionEntity);


        } catch (ClientException $ex) {
            return new JsonResponse(['error' => $ex->getMessage()], 400);
        }

        $customerResponse = $this->transactionManager->normalize($transactionEntity);

        return new JsonResponse($customerResponse);
    }
}
