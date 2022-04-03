<?php

namespace App\Service;

use App\Entity\Customer;
use App\Model\AbstractObjectManager;
use App\Model\EntityManagerInterface;

class CustomerManager extends AbstractObjectManager implements EntityManagerInterface
{

    public function getEntityClass(): string
    {
        return Customer::class;
    }

    public function getRepository(): CustomerRepository
    {
        return new CustomerRepository($this->getOrm(), $this->getEntityClass());
    }

}
