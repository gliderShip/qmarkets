<?php

namespace App\Model;

use App\Service\Orm;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\DeserializationContext;

class AbstractObjectManager
{
    protected Orm $orm;
    protected Serializer $serializer;

    public function __construct()
    {
        $this->orm = new Orm();
        $this->serializer = SerializerBuilder::create()->build();
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
        if(!empty($context)) {
            $serializationContext = SerializationContext::create()->setGroups($context);
        }

        return $this->serializer->toArray($entity, $serializationContext);
    }

    public function getOrm(): Orm
    {
        return new Orm();
    }
}
