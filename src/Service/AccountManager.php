<?php

namespace App\Service;

use App\Entity\Account;
use App\Exceptions\ClientException;
use App\Model\AbstractObjectManager;
use App\Model\EntityInterface;
use App\Model\EntityManagerInterface;

class AccountManager extends AbstractObjectManager implements EntityManagerInterface
{
    private CustomerManager $customerManager;

    public function __construct()
    {
        parent::__construct();
        $this->customerManager = new CustomerManager();
    }

    public function getEntityClass(): string
    {
        return Account::class;
    }

    public function getRepository(): TransactionRepository
    {
        return new TransactionRepository($this->getOrm(), $this->getEntityClass());
    }

    public function createEntity(EntityInterface $account): Account
    {
        $customerId = $account->getCustomerId();
        $customer = $this->customerManager->getEntity($customerId);

        if (!$customer) {
            throw new ClientException("Customer ->:$customerId not found", 404);
        }

        return parent::createEntity($account);
    }

    public function updateEntity(Account $entity): EntityInterface
    {
        return $this->getOrm()->update($entity, 'id', $entity->getId());
    }
}
