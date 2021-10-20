<?php


namespace unit\services;

use Codeception\Test\Unit;
use Codeception\Util\Debug;
use common\dispatchers\DummyDispatcher;
use common\entities\BaggageStatus;
use common\entities\Cell;
use common\repositories\MemoryBaggageRepository;
use common\repositories\MemoryCellsRepository;
use common\services\CellService;
use common\services\dto\CreateCellDto;
use common\services\dto\CreateClientDto;
use DateTimeImmutable;


class BaggageTest extends Unit
{
    public CellService $cellService;

    private function loadCell(): Cell
    {
        $this->cellService = new CellService(
            new MemoryCellsRepository(),
            new MemoryBaggageRepository(),
            new DummyDispatcher()
        );

        $createCellDto = new CreateCellDto();
        $createCellDto->cellName = "#1";
        $createCellDto->cellAddress = "1";
        $cell = $this->cellService->createCell($createCellDto);

        $clientDto = new CreateClientDto();
        $clientDto->phoneCountry = 7;
        $clientDto->phoneCode = '920';
        $clientDto->phoneNumber = '00000001';

        $this->cellService->loadBaggage($clientDto, $cell->getId(), $startDate = new DateTimeImmutable, $daysCount = 42);

        $loadedCell = $this->cellService->getCell($cell->getId());

        $this->assertNotNull($loadedCell->getBaggageId());
        $this->assertNotNull($loadedCell->getPinCode());
        $this->assertEquals($loadedCell->getStartDate(), $startDate);
        $this->assertEquals($loadedCell->getDaysCount(), $daysCount);

        $baggage = $this->cellService->getBaggage($loadedCell->getBaggageId());
        $this->assertNotNull($baggage);
        $this->assertEquals($baggage->getStatus(), BaggageStatus::Loaded);
        $this->assertNotNull($baggage->getClient());


        $client = $baggage->getClient();
        $this->assertEquals($client->getPhone()->getCountry(), $clientDto->phoneCountry);
        $this->assertEquals($client->getPhone()->getCode(), $clientDto->phoneCode);
        $this->assertEquals($client->getPhone()->getNumber(), $clientDto->phoneNumber);


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

}