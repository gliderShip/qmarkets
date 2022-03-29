<?php

namespace App\Controller;

use App\Exceptions\ClientException;
use App\Framework\JsonResponse;
use App\Framework\Request;
use App\Framework\Response;
use App\Service\AccountManager;

class AccountController
{
    private AccountManager $accountManager;

    public function __construct()
    {
        $this->accountManager = new AccountManager();
    }

    public function list()
    {
        $accounts = $this->accountManager->getAccounts();

        return new JsonResponse($accounts);
    }

    public function create(Request $request): Response
    {
        $accountRequest = $request->getJsonData();

        if ($accountRequest === null) {
            return new JsonResponse(['error' => 'Invalid request'], 400);
        }

        $accountEntity = $this->accountManager->denormalize($accountRequest);

        $errors = $accountEntity->getInvalidProperties();
        if (!empty($errors)) {
            return new JsonResponse($errors, 400);
        }

        try {
            $accountEntity = $this->accountManager->createAccount($accountEntity);
        } catch (ClientException $ex) {
            return new JsonResponse(['error' => $ex->getMessage()], 400);
        }

        $customerResponse = $this->accountManager->normalize($accountEntity);

        return new JsonResponse($customerResponse);
    }
}
