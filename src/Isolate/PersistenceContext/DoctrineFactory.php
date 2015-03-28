<?php

namespace Isolate\PersistenceContext;

use Doctrine\Common\Persistence\ManagerRegistry;
use Isolate\PersistenceContext\Transaction\DoctrineFactory as TransactionFactory;

class DoctrineFactory implements Factory
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @var TransactionFactory
     */
    private $transactionFactory;

    /**
     * @param ManagerRegistry $managerRegistry
     * @param TransactionFactory $transactionFactory
     */
    public function __construct(ManagerRegistry $managerRegistry, TransactionFactory $transactionFactory)
    {
        $this->managerRegistry = $managerRegistry;
        $this->transactionFactory = $transactionFactory;
    }

    /**
     * @param string $name
     * @return DoctrineContext
     */
    public function create($name)
    {
        return new DoctrineContext($name, $this->managerRegistry->getManager($name), $this->transactionFactory);
    }
}
