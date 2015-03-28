<?php

namespace spec\Isolate\PersistenceContext\Transaction;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManager;
use Isolate\Exception\UnsupportedManagerException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DoctrineFactorySpec extends ObjectBehavior
{
    function it_creates_orm_transaction_for_entity_manager(EntityManager $entityManager)
    {
        $this->create($entityManager)->shouldReturnAnInstanceOf('Isolate\PersistenceContext\Transaction\Doctrine\ORMTransaction');
    }

    function it_create_odm_transaction_for_document_manager(DocumentManager $documentManager)
    {
        $this->create($documentManager)->shouldReturnAnInstanceOf('Isolate\PersistenceContext\Transaction\Doctrine\ODMTransaction');
    }

    function it_throws_exception_for_unsupported_object_manager()
    {
        $objectManager = new CustomManager();
        $this->shouldThrow(new UnsupportedManagerException("Manager \"spec\\Isolate\\PersistenceContext\\Transaction\\CustomManager\" is not supported."))
            ->during("create", [$objectManager]);
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
