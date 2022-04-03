<?php

namespace App\Model;

use App\Service\Orm;
use JMS\Serializer\Serializer;

interface EntityManagerInterface
{
    public function getOrm(): Orm;

    public function getEntityClass(): string;

    public function getRepository(): RepositoryInterface;

    public function deleteEntity(string $identifier, string $value): int;

    public function getSerializer(): Serializer;

    public function denormalize(array $data, ?array $context = []): ?EntityInterface;

    public function normalize(EntityInterface $entity, ?array $context = []): array;

    public function getAll(): array;

    public function getEntity(string $id): ?EntityInterface;

    public function createEntity(EntityInterface $entity): EntityInterface;
}
