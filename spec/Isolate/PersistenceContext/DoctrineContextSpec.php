<?php

namespace spec\Isolate\PersistenceContext;

use Doctrine\Common\Persistence\ObjectManager;
use Isolate\Exception\NotClosedTransactionException;
use Isolate\Exception\NotOpenedTransactionException;
use Isolate\PersistenceContext\Transaction;
use Isolate\PersistenceContext\Transaction\DoctrineFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DoctrineContextSpec extends ObjectBehavior
{
    const NAME = 'doctrine';

    function let(ObjectManager $objectManager, DoctrineFactory $transactionFactory)
    {
        $this->beConstructedWith(self::NAME, $objectManager, $transactionFactory);
    }

    function it_is_named()
    {
        $this->getName()->shouldReturn(self::NAME);
    }

    function it_open_transactions(DoctrineFactory $transactionFactory, Transaction $transaction)
    {
        $transactionFactory->create(Argument::type('Doctrine\Common\Persistence\ObjectManager'))
            ->willReturn($transaction);

        $this->openTransaction()->shouldReturn($transaction);
    }

    function it_throws_exception_when_opening_transaction_before_closing_old(DoctrineFactory $transactionFactory, Transaction $transaction)
    {
        $transactionFactory->create(Argument::type('Doctrine\Common\Persistence\ObjectManager'))
            ->willReturn($transaction);

        $this->openTransaction()->shouldReturn($transaction);

        $this->shouldThrow(new NotClosedTransactionException())
            ->during('openTransaction');
    }

    function it_throws_exception_when_closing_not_opened_transaction()
    {
        $this->shouldThrow(new NotOpenedTransactionException())
            ->during('closeTransaction');
    }

    function it_close_opened_transaction(DoctrineFactory $transactionFactory, Transaction $transaction)
    {
        $transactionFactory->create(Argument::type('Doctrine\Common\Persistence\ObjectManager'))
            ->willReturn($transaction);

        $transaction->commit()->shouldBeCalled();
        $this->openTransaction();

        $this->closeTransaction();
    }


    function it_throws_exception_when_accessing_not_opened_transaction()
    {
        $this->shouldThrow(new NotOpenedTransactionException())
            ->during('getTransaction');
    }

    function it_returns_opened_transaction(DoctrineFactory $transactionFactory, Transaction $transaction)
    {
        $transactionFactory->create(Argument::type('Doctrine\Common\Persistence\ObjectManager'))
            ->willReturn($transaction);

        $this->openTransaction();

        $this->getTransaction()->shouldReturn($transaction);
    }

    function it_knows_whenever_transaction_is_open_or_not(DoctrineFactory $transactionFactory, Transaction $transaction)
    {
        $transactionFactory->create(Argument::type('Doctrine\Common\Persistence\ObjectManager'))
            ->willReturn($transaction);

        $this->hasOpenTransaction()->shouldReturn(false);
        $this->openTransaction();
        $this->hasOpenTransaction()->shouldReturn(true);
    }
}
