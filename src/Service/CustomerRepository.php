<?php

namespace App\Service;

use App\Entity\Customer;
use App\Model\EntityInterface;
use App\Model\ObjectManagerInterface;
use App\Service\Orm;
use App\Model\RepositoryInterface;

class CustomerRepository implements RepositoryInterface
{
    private Orm $storage;
    private string $entityClass;

    public function __construct(Orm $storage, string $className)
    {
        $this->storage = $storage;
        $this->entityClass = $className;
    }

    public function findAll(): array
    {
        return ['customer1', 'customer2'];
    }

    public function find(int $id): ?EntityInterface
    {
        // TODO: Implement find() method.
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    public function save(EntityInterface $object)
    {
        // TODO: Implement save() method.
    }

    public function delete(EntityInterface $object)
    {
        // TODO: Implement delete() method.
    }

    public function getStorage(): Orm
    {
        // TODO: Implement getStorage() method.
    }


}

