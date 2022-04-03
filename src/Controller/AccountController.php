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

    public function get(Request $request)
    {
        $accountId = $request->getDynamicParameter();

        if (null === $accountId) {
            return new JsonResponse(['error' => 'Account id is missing'], 400);
        }

        $account = $this->accountManager->getEntity($accountId);
        if (null === $account) {
            return new JsonResponse(['error' => "Account ->:$accountId not found!"], 404);
        }

        $accountResponse = $this->accountManager->normalize($account);
        return new JsonResponse($accountResponse);
    }

    public function list()
    {
        $accounts = $this->accountManager->getAll();

        return new JsonResponse($accounts);
    }

    public function create(Request $request): Response
    {
        $accountRequest = $request->getJsonData();

        if ($accountRequest === null) {
            return new JsonResponse(['error' => 'Invalid request'], 400);
        }

        try {
            $accountEntity = $this->accountManager->denormalize($accountRequest);
            $errors = $accountEntity->getInvalidProperties();
            if (!empty($errors)) {
                return new JsonResponse($errors, 400);
            }
            $accountEntity = $this->accountManager->createEntity($accountEntity);
        } catch (ClientException $ex) {
            return new JsonResponse(['error' => $ex->getMessage()], 400);
        }

        $customerResponse = $this->accountManager->normalize($accountEntity);

        return new JsonResponse($customerResponse);
    }

    public function update(Request $request): Response
    {
        $accountId = $request->getDynamicParameter();

        if (null === $accountId) {
            return new JsonResponse(['error' => 'Account id is missing'], 400);
        }

        $account = $this->accountManager->getEntity($accountId);
        if (null === $account) {
            return new JsonResponse(['error' => "Account ->:$accountId not found!"], 404);
        }

        $updateRequest = $request->getJsonData();

        if ($updateRequest === null) {
            return new JsonResponse(['error' => 'Invalid request'], 400);
        }

        $updatedEntity = $this->accountManager->denormalize($updateRequest);
        $updatedEntity->setId($accountId);

        try {
            $errors = $updatedEntity->getInvalidProperties();
            if (!empty($errors)) {
                return new JsonResponse($errors, 400);
            }
            $updatedEntity = $this->accountManager->updateEntity($updatedEntity);
        } catch (ClientException $ex) {
            return new JsonResponse(['error' => $ex->getMessage()], 400);
        }

        $customerResponse = $this->accountManager->normalize($updatedEntity);

        return new JsonResponse($customerResponse);
    }

    public function delete(Request $request)
    {
        $accountId = $request->getDynamicParameter();

        if (null === $accountId) {
            return new JsonResponse(['error' => 'Account id is missing'], 400);
        }

        $deletedRecords = $this->accountManager->deleteEntity('id', $accountId);
        if ($deletedRecords == 0) {
            return new JsonResponse(['error' => "Account ->:$accountId not found"], 404);
        }

        return new JsonResponse(null, 204);
    }
}
