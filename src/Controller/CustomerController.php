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

    public function list()
    {
        $customers = $this->customerManager->getCustomers();

        return new JsonResponse($customers);
    }

    public function create(Request $request): Response
    {
        $customerRequest = $request->getJsonData();

        if($customerRequest === null) {
            return new JsonResponse(['error' => 'Invalid request'], 400);
        }

        $customerEntity = $this->customerManager->denormalize($customerRequest);

        $errors = $customerEntity->getInvalidProperties();
        if (!empty($errors)) {
            return new JsonResponse($errors, 400);
        }

        $customerEntity = $this->customerManager->createCustomer($customerEntity);
        $customerResponse = $this->customerManager->normalize($customerEntity);
        return new JsonResponse($customerResponse);
    }
}
