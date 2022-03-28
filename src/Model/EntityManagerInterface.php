<?php

namespace App\Model;

use App\Service\Orm;

interface EntityManagerInterface
{
    public function getEntityClass(): string;

    public function denormalize(array $data, ?array $context = []): ?EntityInterface;

    public function getRepository(): RepositoryInterface;

    public function getOrm(): Orm;
}
