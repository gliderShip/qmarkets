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

    public function add(Request $request): Response
    {
        $customerRequest = $request->getJsonData();

        $customerEntity = $this->customerManager->denormalize($customerRequest);
        if (!empty($customerEntity->getInvalidProperties())) {
            return new JsonResponse($customerEntity->getInvalidProperties(), 400);
        }

        $customerEntity = $this->customerManager->createCustomer($customerEntity);
        die(var_dump($customerEntity));
        $customerResponse = $this->customerManager->normalize($customerEntity);
        return new JsonResponse($customerResponse);
    }
}
