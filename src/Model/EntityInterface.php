<?php

namespace App\Model;

interface EntityInterface
{
    public function getId(): string;
    public function setId(string $id): void;

    public function getInvalidProperties(): array;
}
