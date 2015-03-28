<?php

namespace Isolate\PersistenceContext\Transaction\Doctrine;

use Doctrine\ODM\MongoDB\DocumentManager;
use Isolate\Exception\UnsupportedOperationException;
use Isolate\PersistenceContext\Transaction;

final class ODMTransaction implements Transaction
{
    /**
     * @var DocumentManager
     */
    private $documentManager;

    /**
     * @param DocumentManager $documentManager
     */
    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    /**
     * @return void
     */
    public function commit()
    {
        $this->documentManager->flush();
    }

    /**
     * @throws UnsupportedOperationException
     */
    public function rollback()
    {
        throw new UnsupportedOperationException(
            "Doctrine ODM does not support rollbacks. If you really need them your should consider using ORM instead."
        );
    }

    /**
     * @param mixed $entity
     * @return boolean
     */
    public function contains($entity)
    {
        return $this->documentManager->contains($entity);
    }

    /**
     * @param mixed $entity
     */
    public function persist($entity)
    {
        $this->documentManager->persist($entity);
    }

    /**
     * @param mixed $entity
     */
    public function delete($entity)
    {
        $this->documentManager->remove($entity);
    }
}
