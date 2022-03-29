<?php

namespace App\Service;

use App\Entity\Customer;
use App\Model\AbstractObjectManager;
use App\Model\EntityInterface;
use App\Model\EntityManagerInterface;

class CustomerManager extends AbstractObjectManager implements EntityManagerInterface
{
    public function getCustomers(): array
    {
        return $this->getRepository()->findAll();
    }

    public function createCustomer(Customer $customer): Customer
    {
        return $this->orm->insert($customer);
    }

    public function getCustomer(string $id): ?Customer
    {
        return $this->getRepository()->findById($id);
    }

    public function getEntityClass(): string
    {
        return Customer::class;
    }

    public function getRepository(): CustomerRepository
    {
        return new CustomerRepository($this->getOrm(), $this->getEntityClass());
    }

    public function denormalize(array $data, ?array $context = []): ?EntityInterface
    {
        return $this->denormalizeData($data, Customer::class, $context);
    }

    public function normalize(EntityInterface $entity, ?array $context = []): array
    {
        return $this->toArray($entity, $context);
    }
}
