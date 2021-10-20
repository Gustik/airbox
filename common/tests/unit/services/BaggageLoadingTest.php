<?php


namespace unit\services;

use Codeception\Test\Unit;
use Codeception\Util\Debug;
use common\dispatchers\DummyDispatcher;
use common\entities\BaggageStatus;
use common\entities\Cabinet;
use common\entities\Cell;
use common\entities\Id;
use common\repositories\MemoryBaggageRepository;
use common\repositories\MemoryCellsRepository;
use common\services\CellService;
use common\services\dto\CreateClientDto;
use DateTimeImmutable;


class BaggageLoadingTest extends Unit
{
    public MemoryCellsRepository $cells;
    public CellService $cellService;
    public MemoryBaggageRepository $baggies;

    private function loadCell(): Cell
    {
        $this->cells = new MemoryCellsRepository();
        $cell = new Cell(
            $cellId = Id::next(),
            $cabinet = new Cabinet(
                $cabinetId = Id::next(),
                $cabinetName = "Шкаф №1",
                $cabinetAddress = "001"
            ),
            $cellName = "Ячейка №15",
            $cellAddress = "0015"
        );
        $this->cells->add($cell);

        $this->cellService = new CellService(
            $this->cells,
            $this->baggies = new MemoryBaggageRepository(),
            new DummyDispatcher()
        );

        $clientDto = new CreateClientDto();
        $clientDto->phoneCountry = 7;
        $clientDto->phoneCode = '920';
        $clientDto->phoneNumber = '00000001';

        $this->cellService->loadBaggage($clientDto, $cellId->getId(), $startDate = new DateTimeImmutable, $daysCount = 42);

        $loadedCell = $this->cells->get($cellId);

        $this->assertNotNull($loadedCell->getBaggageId());
        $this->assertNotNull($loadedCell->getPinCode());
        $this->assertEquals($loadedCell->getStartDate(), $startDate);
        $this->assertEquals($loadedCell->getDaysCount(), $daysCount);

        $baggage = $this->baggies->get($loadedCell->getBaggageId());
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
        $cell = $this->cells->get($cell->getId());

        $this->assertNull($cell->getBaggageId());
        $this->assertNull($cell->getStartDate());
        $this->assertEquals($cell->getDaysCount(), 0);
        $this->assertEquals($cell->getPinCode(), '');

        $baggage = $this->baggies->get($baggageId);
        $this->assertEquals($baggage->getStatus(), BaggageStatus::Unloaded);
    }

}