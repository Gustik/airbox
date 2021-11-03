<?php

namespace common\tests\unit\repositories;


use Codeception\Test\Unit;
use common\entities\Baggage;
use common\entities\Id;
use common\repositories\BaggageRepository;
use common\repositories\NotFoundException;

abstract class BaseBaggageRepositoryTest extends Unit
{
    /**
     * @var BaggageRepository
     */
    protected $repository;


    public function testGet(): void
    {
        $baggage = new Baggage(
            Id::next(),
            new \DateTimeImmutable(),
            '71234567890',
        );
        $this->repository->add($baggage);

        $found = $this->repository->get($baggage->getId());

        $this->assertNotNull($found);
        $this->assertEquals($baggage->getId(), $found->getId());
    }

    public function testGetNotFound(): void
    {
        $this->expectException(NotFoundException::class);

        $this->repository->get(new Id(uniqid()));
    }

    public function testAdd(): void
    {
        $baggage = new Baggage(
            Id::next(),
            new \DateTimeImmutable(),
            '71234567890',
        );

        $this->repository->add($baggage);

        $found = $this->repository->get($baggage->getId());

        $this->assertEquals($baggage->getId(), $found->getId());
        $this->assertEquals($baggage->getPhone(), $found->getPhone());
        $this->assertEquals($baggage->getStatus(), $found->getStatus());
        $this->assertEquals($baggage->getDate()->format("Y-m-d H:i:s"), $found->getDate()->format("Y-m-d H:i:s"));

    }

    public function testSave(): void
    {
        $baggage = new Baggage(
            Id::next(),
            new \DateTimeImmutable(),
            '71234567890',
        );
        $this->repository->add($baggage);

        $edit = $this->repository->get($baggage->getId());

        $edit->unload();

        $this->repository->save($edit);

        $found = $this->repository->get($baggage->getId());

        $this->assertTrue($found->isUnloaded());
    }

    public function testRemove(): void
    {
        $id = new Id(uniqid());
        $baggage = new Baggage(
            Id::next(),
            new \DateTimeImmutable(),
            '71234567890',
        );
        $this->repository->add($baggage);

        $this->repository->remove($baggage);

        $this->expectException(NotFoundException::class);

        $this->repository->get($id);
    }
}