<?php

namespace common\tests\unit\repositories;


use Codeception\Test\Unit;
use common\entities\Cell;
use common\entities\Id;
use common\repositories\CellRepository;
use common\repositories\NotFoundException;

abstract class BaseCellsRepositoryTest extends Unit
{
    /**
     * @var CellRepository
     */
    protected $repository;


    public function testGet(): void
    {
        $cell = new Cell(
            Id::next(),
            '1',
            '1',
            0,
            100,
        );
        $this->repository->add($cell);

        $found = $this->repository->get($cell->getId());

        $this->assertNotNull($found);
        $this->assertEquals($cell->getId(), $found->getId());
    }

    public function testGetNotFound(): void
    {
        $this->expectException(NotFoundException::class);

        $this->repository->get(new Id(uniqid()));
    }

    public function testAdd(): void
    {
        $cell = new Cell(
            Id::next(),
            '1',
            '1',
            0,
            100,
            );

        $this->repository->add($cell);

        $found = $this->repository->get($cell->getId());

        $this->assertEquals($cell->getId(), $found->getId());
        $this->assertEquals($cell->getName(), $found->getName());
        $this->assertEquals($cell->getAddress(), $found->getAddress());

    }

    public function testSave(): void
    {
        $cell = new Cell(
            Id::next(),
            '1',
            '1',
            0,
            100,
            );

        $this->repository->add($cell);

        $edit = $this->repository->get($cell->getId());

        $edit->lock();

        $this->repository->save($edit);

        $found = $this->repository->get($cell->getId());

        $this->assertTrue($found->isLocked());
    }

    public function testRemove(): void
    {
        $id = new Id(uniqid());
        $cell = new Cell(
            Id::next(),
            '1',
            '1',
            0,
            100,
            );
        $this->repository->add($cell);

        $this->repository->remove($cell);

        $this->expectException(NotFoundException::class);

        $this->repository->get($id);
    }
}