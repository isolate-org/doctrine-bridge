<?php

namespace Isolate\PersistenceContext;

use Doctrine\Common\Persistence\ObjectManager;
use Isolate\Exception\NotClosedTransactionException;
use Isolate\Exception\NotOpenedTransactionException;
use Isolate\PersistenceContext;
use Isolate\PersistenceContext\Transaction;

final class DoctrineContext implements PersistenceContext
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Transaction|null
     */
    private $transaction;

    /**
     * @var Transaction\DoctrineFactory
     */
    private $transactionFactory;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @param string $name
     * @param ObjectManager $objectManager
     * @param Transaction\DoctrineFactory $transactionFactory
     */
    public function __construct($name, ObjectManager $objectManager, Transaction\DoctrineFactory $transactionFactory)
    {
        $this->name = $name;
        $this->transactionFactory = $transactionFactory;
        $this->objectManager = $objectManager;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Transaction
     * @throws NotClosedTransactionException
     */
    public function openTransaction()
    {
        if (!is_null($this->transaction)) {
            throw new NotClosedTransactionException();
        }

        $this->transaction = $this->transactionFactory->create($this->objectManager);

        return $this->transaction;
    }

    /**
     * @return boolean
     */
    public function hasOpenTransaction()
    {
        return !is_null($this->transaction);
    }

    /**
     * @return Transaction
     * @throws NotOpenedTransactionException
     */
    public function getTransaction()
    {
        if (is_null($this->transaction)) {
            throw new NotOpenedTransactionException();
        }

        return $this->transaction;
    }

    /**
     * @throws NotOpenedTransactionException
     */
    public function closeTransaction()
    {
        if (is_null($this->transaction)) {
            throw new NotOpenedTransactionException();
        }

        $this->transaction->commit();

        unset($this->transaction);
    }
}
