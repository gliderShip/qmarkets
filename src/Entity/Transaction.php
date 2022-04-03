<?php

namespace App\Entity;

use App\Exceptions\ClientException;
use App\Model\EntityInterface;
use JMS\Serializer\Annotation as Serializer;

class Transaction implements EntityInterface
{
    private string $id;

    protected string $sourceAccountId;

    protected string $destinationAccountId;

    protected int $amount;


    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getSourceAccountId(): string
    {
        return $this->sourceAccountId;
    }

    public function setSourceAccountId(string $sourceAccountId): void
    {
        $this->sourceAccountId = $sourceAccountId;
    }

    public function getDestinationAccountId(): string
    {
        return $this->destinationAccountId;
    }

    public function setDestinationAccountId(string $destinationAccountId): void
    {
        $this->destinationAccountId = $destinationAccountId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getInvalidProperties(): array
    {
        $invalidProperties = [];

        if (empty($this->sourceAccountId)) {
            $invalidProperties['error'][] = [
                'property' => 'sourceAccountId',
                'invalidValue' => $this->sourceAccountId ?? null,
                'message' => 'Source account id is required',
            ];
        }
        if(empty($this->destinationAccountId)) {
            $invalidProperties['error'][] = [
                'property' => 'destinationAccountId',
                'invalidValue' => $this->destinationAccountId ?? null,
                'message' => 'Destination account id is required',
            ];
        }
        if($this->sourceAccountId === $this->destinationAccountId) {
            $invalidProperties['error'][] = [
                'property' => 'destinationAccountId',
                'invalidValue' => $this->destinationAccountId ?? null,
                'message' => 'Destination account must be different from source account',
            ];
        }
        if(empty($this->amount) || $this->amount <= 0) {
            $invalidProperties['error'][] = [
                'property' => 'amount',
                'invalidValue' => $this->amount ?? null,
                'message' => 'Amount should be greater than 0',
            ];
        }




        return $invalidProperties;
    }

}
