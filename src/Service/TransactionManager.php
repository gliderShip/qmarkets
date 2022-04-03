<?php

namespace App\Service;

use App\Entity\Account;
use App\Entity\Transaction;
use App\Exceptions\ClientException;
use App\Model\AbstractObjectManager;
use App\Model\EntityInterface;
use App\Model\EntityManagerInterface;

class TransactionManager extends AbstractObjectManager implements EntityManagerInterface
{
    private AccountManager $accountManager;

    public function __construct()
    {
        parent::__construct();
        $this->accountManager = new AccountManager();
    }

    public function getEntityClass(): string
    {
        return Transaction::class;
    }

    public function getRepository(): TransactionRepository
    {
        return new TransactionRepository($this->getOrm(), $this->getEntityClass());
    }

    public function createEntity(EntityInterface $transaction): EntityInterface
    {
        /** @var Transaction $transaction */
        $sourceAccountId = $transaction->getSourceAccountId();
        $destinationAccountId = $transaction->getDestinationAccountId();
        $amount = $transaction->getAmount();

        /** @var Account $sourceAccount */
        $sourceAccount = $this->accountManager->getEntity($sourceAccountId);
        /** @var Account $destinationAccount */
        $destinationAccount = $this->accountManager->getEntity($destinationAccountId);

        if($sourceAccount->getBalance() < $amount) {
            throw new ClientException("Insufficient funds! Current balance ->:".$sourceAccount->getBalance());
        }

        $transactionStarted = $this->orm->startTransaction();
        if(!$transactionStarted) {
            throw new \RuntimeException('Transaction start failed');
        }

        try {
            $sourceAccount->subtractBalance($amount);
            $destinationAccount->addBalance($amount);
            $this->accountManager->updateEntity($sourceAccount);
            $this->accountManager->updateEntity($destinationAccount);
            $transaction = $this->orm->insert($transaction);
        } catch (\Exception $e) {
            $this->orm->rollback();
            throw $e;
        }

        $this->orm->commit();

        return $transaction;
    }

    public function getAccountTransactions(Account $account): array
    {
        $id = $account->getId();
        return $this->getRepository()->findAllBy(
            [
                'sourceAccountId' => $id,
                'destinationAccountId' => $id,
            ],
            Orm::CRITERIA_TYPE_OR);
    }

}
