<?php


namespace common\tests\unit\services;

use Codeception\Test\Unit;
use common\dispatchers\DummyDispatcher;
use common\entities\BaggageStatus;
use common\entities\Cell;
use common\entities\CellStatus;
use common\repositories\MemoryBaggageRepository;
use common\repositories\MemoryCellsRepository;
use common\services\CellService;
use common\services\dto\CreateCellDto;
use common\services\dto\CreateClientDto;
use DateTimeImmutable;


class BaggageTest extends Unit
{
    public CellService $cellService;

    protected function _before()
    {
        $this->cellService = new CellService(
            new MemoryCellsRepository(),
            new MemoryBaggageRepository(),
            new DummyDispatcher()
        );
        parent::_before();
    }

    private function createCell(): Cell
    {
        $createCellDto = new CreateCellDto();
        $createCellDto->cellName = "#1";
        $createCellDto->cellAddress = "1";
        $createCellDto->daysCount = 42;
        $createCellDto->price = 142;

        return $this->cellService->createCell($createCellDto);
    }

    private function loadCell(): Cell
    {
        $cell = $this->createCell();
        $cell = $this->cellService->reserveCell($cell);

        $phone = '792000000001';

        $this->cellService->loadBaggage($phone, $cell->getId(), $startDate = new DateTimeImmutable, $daysCount = 42);

        $loadedCell = $this->cellService->getCell($cell->getId());

        $this->assertNotNull($loadedCell->getBaggageId());
        $this->assertNotNull($loadedCell->getPinCode());
        $this->assertEquals($loadedCell->getStartDate(), $startDate);
        $this->assertEquals($loadedCell->getDaysCount(), $daysCount);
        $this->assertEquals($loadedCell->getStatus(), CellStatus::Unlocked);

        $baggage = $this->cellService->getBaggage($loadedCell->getBaggageId());
        $this->assertNotNull($baggage);
        $this->assertEquals($baggage->getStatus(), BaggageStatus::Loaded);
        $this->assertEquals($baggage->getPhone(), $phone);


        return $loadedCell;
    }

    public function testLoading(): void
    {
        $this->loadCell();
    }

    public function testUnloading(): void
    {
        $cell = $this->loadCell();
        $baggageId = $cell->getBaggageId();

        $this->cellService->unloadBaggage($cell->getId());
        $cell = $this->cellService->getCell($cell->getId());

        $this->assertNull($cell->getBaggageId());
        $this->assertNull($cell->getStartDate());
        $this->assertEquals($cell->getDaysCount(), 0);
        $this->assertEquals($cell->getPinCode(), '');

        $baggage = $this->cellService->getBaggage($baggageId);
        $this->assertEquals($baggage->getStatus(), BaggageStatus::Unloaded);
    }

    public function testReserveCell(): void
    {
        $cell = $this->createCell();
        $this->cellService->reserveCell($cell);
        $cell = $this->cellService->getCell($cell->getId());
        $this->assertEquals($cell->getStatus(), CellStatus::Reserved);
    }

    public function testReserveReservedCell(): void
    {
        $cell = $this->createCell();
        $cell = $this->cellService->reserveCell($cell);
        $this->expectException(\DomainException::class);
        $this->cellService->reserveCell($cell);
    }

    public function testListCellDto()
    {
        $cell = $this->createCell();
        $this->createCell();
        $cell2 = $this->createCell();
        $cellsDto = $this->cellService->cellListDto();
        $this->assertEquals($cellsDto[0]->cellName, $cell->getName());
        $this->assertEquals($cellsDto[2]->cellName, $cell2->getName());
    }
}