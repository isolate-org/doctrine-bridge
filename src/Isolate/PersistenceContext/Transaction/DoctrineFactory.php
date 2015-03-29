<?php

namespace Isolate\PersistenceContext\Transaction;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Isolate\Exception\UnsupportedManagerException;
use Isolate\PersistenceContext;
use Isolate\PersistenceContext\Transaction;

class DoctrineFactory implements Factory
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @param PersistenceContext $context
     * @return Doctrine\ODMTransaction|Doctrine\ORMTransaction
     * @throws UnsupportedManagerException
     */
    public function create(PersistenceContext $context)
    {
        $objectManager = $this->managerRegistry->getManager((string) $context->getName());
        if (interface_exists('Doctrine\ORM\EntityManagerInterface') && is_a($objectManager, 'Doctrine\ORM\EntityManagerInterface')) {
            return new Transaction\Doctrine\ORMTransaction($objectManager);
        }

        if (class_exists('Doctrine\ODM\MongoDB\DocumentManager') && is_a($objectManager, 'Doctrine\ODM\MongoDB\DocumentManager')) {
            return new Transaction\Doctrine\ODMTransaction($objectManager);
        }

        throw new UnsupportedManagerException(sprintf("Manager \"%s\" is not supported.", get_class($objectManager)));
    }
}
