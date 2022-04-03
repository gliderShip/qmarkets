<?php

namespace App\Model;

use App\Entity\Account;
use App\Service\Orm;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;

abstract class AbstractObjectManager
{
    protected Orm $orm;
    protected Serializer $serializer;

    public function __construct()
    {
        $this->orm = new Orm();
        $this->serializer = SerializerBuilder::create()
            ->setPropertyNamingStrategy(
                new SerializedNameAnnotationStrategy(
                    new IdenticalPropertyNamingStrategy()
                )
            )
            ->build();
    }

    public abstract function getEntityClass(): string;

    public abstract function getRepository(): RepositoryInterface;

    public function getOrm(): Orm
    {
        return new Orm();
    }

    public function deleteEntity(string $identifier, string $value): int
    {
        return $this->orm->delete($this->getEntityClass(), $identifier, $value);
    }

    public function getSerializer(): Serializer
    {
        return $this->serializer;
    }

    protected function denormalizeData(array $data, string $className, array $context = []): EntityInterface
    {
        $deserializationContext = null;
        if (!empty($context)) {
            $deserializationContext = DeserializationContext::create()->setGroups($context);
        }

        return $this->serializer->fromArray($data, $className, $deserializationContext);
    }

    protected function toArray(EntityInterface $entity, array $context = []): array
    {
        $serializationContext = null;
        if (!empty($context)) {
            $serializationContext = SerializationContext::create()->setGroups($context);
        }

        return $this->serializer->toArray($entity, $serializationContext);
    }

    public function denormalize(array $data, ?array $context = []): ?EntityInterface
    {
        return $this->denormalizeData($data, $this->getEntityClass(), $context);
    }

    public function normalize(EntityInterface $entity, ?array $context = []): array
    {
        return $this->toArray($entity, $context);
    }

    public function getAll(): array
    {
        return $this->getRepository()->findAll();
    }

    public function getEntity(string $id): ?EntityInterface
    {
        return $this->getRepository()->findByPrimaryId($id);
    }

    public function createEntity(EntityInterface $entity): EntityInterface
    {
        return $this->orm->insert($entity);
    }
}
