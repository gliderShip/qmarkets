<?php

namespace App\Service;

use App\Model\EntityInterface;
use App\Model\RepositoryInterface;
use App\Model\RepositoryTrait;

class AccountRepository implements RepositoryInterface
{
    use RepositoryTrait;

    private Orm $storage;
    private string $entityClass;

    public function __construct(Orm $storage, string $entityClass)
    {
        $this->storage = $storage;
        $this->entityClass = $entityClass;
    }



}

