<?php

namespace App\Model;

use App\Service\Orm;

interface RepositoryInterface
{
    public function findAll(): array;

    public function findAllBy(array $criteria, string $criteriaType): array;

    public function findByPrimaryId(string $id): ?EntityInterface;

    public function getEntityClass(): string;

}
