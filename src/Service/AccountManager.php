<?php

namespace App\Service;

use App\Entity\Account;
use App\Exceptions\ClientException;
use App\Model\AbstractObjectManager;
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

    public function getAccounts(): array
    {
        return $this->getRepository()->findAll();
    }

    public function getRepository(): AccountRepository
    {
        return new AccountRepository($this->getOrm(), $this->getEntityClass());
    }

    public function denormalize(array $data, ?array $context = []): ?Account
    {
        return $this->denormalizeData($data, Account::class, $context);
    }

    public function normalize(Account $entity, ?array $context = []): array
    {
        return $this->toArray($entity, $context);
    }

    public function createAccount(Account $account): Account
    {
        $customerId = $account->getCustomerId();
        $customer = $this->customerManager->getCustomer($customerId);

        if (!$customer) {
            throw new ClientException("Customer ->:$customerId not found", 404);
        }

        return $this->orm->insert($account);
    }
}
