<?php

namespace spec\Isolate\PersistenceContext\Transaction;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManager;
use Isolate\Exception\UnsupportedManagerException;
use Isolate\PersistenceContext;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DoctrineFactorySpec extends ObjectBehavior
{
    public function let(ManagerRegistry $managerRegistry, PersistenceContext $persistenceContext)
    {
        $persistenceContext->getName()->willReturn(new PersistenceContext\Name('doctrine'));
        $this->beConstructedWith($managerRegistry);
    }

    function it_creates_orm_transaction_for_entity_manager(ManagerRegistry $managerRegistry, PersistenceContext $persistenceContext, EntityManager $entityManager)
    {
        $managerRegistry->getManager('doctrine')->willReturn($entityManager);
        $this->create($persistenceContext)->shouldReturnAnInstanceOf('Isolate\PersistenceContext\Transaction\Doctrine\ORMTransaction');
    }

    function it_create_odm_transaction_for_document_manager(ManagerRegistry $managerRegistry, PersistenceContext $persistenceContext, DocumentManager $documentManager)
    {
        $managerRegistry->getManager('doctrine')->willReturn($documentManager);
        $this->create($persistenceContext)->shouldReturnAnInstanceOf('Isolate\PersistenceContext\Transaction\Doctrine\ODMTransaction');
    }

    function it_throws_exception_for_unsupported_object_manager(ManagerRegistry $managerRegistry, PersistenceContext $persistenceContext)
    {
        $managerRegistry->getManager('doctrine')->willReturn(new CustomManager());
        $this->shouldThrow(new UnsupportedManagerException("Manager \"spec\\Isolate\\PersistenceContext\\Transaction\\CustomManager\" is not supported."))
            ->during("create", [$persistenceContext]);
    }
}

class CustomManager implements ObjectManager
{
    public function find($className, $id) {}
    public function persist($object) {}
    public function remove($object){}
    public function merge($object){}
    public function clear($objectName = null){}
    public function detach($object){}
    public function refresh($object){}
    public function flush(){}
    public function getRepository($className){}
    public function getClassMetadata($className){}
    public function getMetadataFactory(){}
    public function initializeObject($obj){}
    public function contains($object){}
}
