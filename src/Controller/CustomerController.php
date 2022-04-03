<?php

namespace App\Controller;

use App\Framework\JsonResponse;
use App\Framework\Request;
use App\Framework\Response;
use App\Service\CustomerManager;

class CustomerController
{
    private CustomerManager $customerManager;

    public function __construct()
    {
        $this->customerManager = new CustomerManager();
    }

    public function get(Request $request)
    {
        $customerId = $request->getDynamicParameter();

        if (null === $customerId) {
            return new JsonResponse(['error' => 'Customer id is missing'], 400);
        }

        $customer = $this->customerManager->getEntity($customerId);
        if (null === $customer) {
            return new JsonResponse(['error' => 'Customer not found'], 404);
        }

        $customerResponse = $this->customerManager->normalize($customer);
        return new JsonResponse($customerResponse);
    }

    public function list()
    {
        $customers = $this->customerManager->getAll();

        return new JsonResponse($customers);
    }

    public function create(Request $request): Response
    {
        $customerRequest = $request->getJsonData();

        if ($customerRequest === null) {
            return new JsonResponse(['error' => 'Invalid request'], 400);
        }

        $customerEntity = $this->customerManager->denormalize($customerRequest);

        $errors = $customerEntity->getInvalidProperties();
        if (!empty($errors)) {
            return new JsonResponse($errors, 400);
        }

        $customerEntity = $this->customerManager->createEntity($customerEntity);
        $customerResponse = $this->customerManager->normalize($customerEntity);
        return new JsonResponse($customerResponse);
    }

    public function delete(Request $request)
    {
        $customerId = $request->getDynamicParameter();

        if (null === $customerId) {
            return new JsonResponse(['error' => 'Customer id is missing'], 400);
        }

        $deletedRecords = $this->customerManager->deleteEntity('id', $customerId);
        if ($deletedRecords == 0) {
            return new JsonResponse(['error' => "Customer ->:$customerId not found"], 404);
        }

        return new JsonResponse(null, 204);
    }
}
