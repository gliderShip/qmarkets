<?php

namespace App\Entity;

use App\Model\EntityInterface;
use JMS\Serializer\Annotation as Serializer;

class Account implements EntityInterface
{
    private string $id;

    protected ?string $customerId = null;

    protected int $balance = 0;

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    /**
     * @param string $customerId
     */
    public function setCustomerId(string $customerId): void
    {
        $this->customerId = $customerId;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function setBalance(int $balance): void
    {
        $this->balance = $balance;
    }

    public function getInvalidProperties(): array
    {
        $invalidProperties = [];

        if (empty($this->customerId)) {

            $invalidProperties['error'][] = [
                'property' => 'customerId',
                'invalidValue' => $this->customerId ?? null,
                'message' => 'Customer ID is required',
            ];
        }

        return $invalidProperties;
    }
}
