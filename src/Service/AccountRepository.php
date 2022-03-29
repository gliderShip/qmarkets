<?php

namespace App\Service;

use App\Model\EntityInterface;
use App\Model\RepositoryInterface;

class AccountRepository implements RepositoryInterface
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
        return $this->getStorage()->selectAll($this->entityClass);
    }

    public function findById(string $id): ?EntityInterface
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
        return $this->storage;
    }


}

