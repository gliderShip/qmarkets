<?php

namespace App\Entity;

use App\Model\EntityInterface;

class Customer implements EntityInterface
{
    private ?string $id = null;

    private string $name;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }


    public function getInvalidProperties(): array
    {
        $invalidProperties = [];

        if (empty($this->name)) {
            $invalidProperties['errors'][]['property'] = 'name';
            $invalidProperties['errors'][]['value'] = $this->name ?? null;
            $invalidProperties['errors'][]['message'] = 'Name is required';
        }

        return $invalidProperties;
    }
}
