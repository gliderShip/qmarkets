<?php

namespace App\Model;

use App\Service\Orm;

trait RepositoryTrait
{
    public function findAll(): array
    {
        return $this->getStorage()->selectAll($this->getEntityClass());
    }

    public function findAllBy(array $criteria, string $criteriaType): array
    {
        return $this->getStorage()->findAllBy($this->getEntityClass(), $criteria,  $criteriaType);
    }

    public function findByPrimaryId(string $id): ?EntityInterface
    {
        return $this->getStorage()->findOneBy($this->entityClass, 'id', $id);
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    public function getStorage(): Orm
    {
        return $this->storage;
    }
}
