<?php

namespace App\Entity;

use App\Exceptions\ClientException;
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

    public function setId(string $id): void
    {
        $this->id = $id;
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

    /**
     * @Serializer\SerializedName("balance")
     */
    public function getBalance(): int
    {
        return $this->balance;
    }

    public function setBalance($balance): void
    {
        if (!is_int($balance) && !ctype_digit($balance)) {
            throw new ClientException('Balance must be a positive integer (cents or equivalent in other currencies)');
        }

        $this->balance = (int)$balance;
    }

    public function addBalance(int $balance): int
    {
        if ($balance < 0) {
            throw new \Exception("Balance ->:$balance should not be negative!");
        }

        $this->balance += $balance;
        return $this->balance;
    }

    public function subtractBalance(int $balance): int
    {
        if ($balance < 0) {
            throw new \Exception("Balance ->:$balance should not be negative!");
        }

        if ($this->balance < $balance) {
            throw new \Exception("Insufficient funds! Current balance ->:$this->balance. Can not subtract ->:$balance");
        }

        $this->balance -= $balance;
        return $this->balance;
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
        if (!is_int($this->getBalance()) || $this->getBalance() < 0) {
            $invalidProperties['error'][] = [
                'property' => 'balance',
                'invalidValue' => $this->balance ?? null,
                'message' => 'Balance must be a positive integer (cents or equivalent in other currencies)!',
            ];
        }

        return $invalidProperties;
    }
}
