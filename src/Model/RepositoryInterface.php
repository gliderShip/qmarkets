<?php

namespace App\Model;

interface RepositoryInterface
{
    /**
     * @param int $id
     * @return EntityInterface[]
     */
    public function findAll(): array;

    public function find(int $id): ?EntityInterface;

    public function getEntityClass(): string;

    public function save(EntityInterface $object);

    public function delete(EntityInterface $object);
}
