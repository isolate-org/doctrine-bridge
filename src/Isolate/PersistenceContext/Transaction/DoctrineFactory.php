<?php

namespace Isolate\PersistenceContext\Transaction;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Isolate\Exception\UnsupportedManagerException;
use Isolate\PersistenceContext\Transaction;

class DoctrineFactory
{
    /**
     * @param ObjectManager $objectManager
     * @return Doctrine\ODMTransaction|Doctrine\ORMTransaction
     * @throws UnsupportedManagerException
     */
    public function create(ObjectManager $objectManager)
    {
        if (interface_exists('Doctrine\ORM\EntityManagerInterface') && is_a($objectManager, 'Doctrine\ORM\EntityManagerInterface')) {
            return new Transaction\Doctrine\ORMTransaction($objectManager);
        }

        if (class_exists('Doctrine\ODM\MongoDB\DocumentManager') && is_a($objectManager, 'Doctrine\ODM\MongoDB\DocumentManager')) {
            return new Transaction\Doctrine\ODMTransaction($objectManager);
        }

        throw new UnsupportedManagerException(sprintf("Manager \"%s\" is not supported.", get_class($objectManager)));
    }
}
