<?php

namespace Isolate\PersistenceContext\Transaction\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Isolate\PersistenceContext\Transaction;

final class ORMTransaction implements Transaction
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->entityManager->beginTransaction();
    }

    /**
     * @return void
     */
    public function commit()
    {
        $this->entityManager->flush();
    }

    /**
     * @return void
     */
    public function rollback()
    {
        $this->entityManager->rollback();
    }

    /**
     * @param mixed $entity
     * @return boolean
     */
    public function contains($entity)
    {
        return $this->entityManager->contains($entity);
    }

    /**
     * @param mixed $entity
     */
    public function persist($entity)
    {
        $this->entityManager->persist($entity);
    }

    /**
     * @param mixed $entity
     */
    public function delete($entity)
    {
        $this->entityManager->remove($entity);
    }
}
