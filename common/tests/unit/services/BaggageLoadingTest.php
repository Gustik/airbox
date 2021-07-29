<?php


namespace unit\services;

use Codeception\Test\Unit;
use common\dispatchers\DummyDispatcher;
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
    public function testLoading(): void
    {
        $cells = new MemoryCellsRepository();
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
        $cells->add($cell);

        $clientDto = new CreateClientDto();
        $clientDto->lastName = 'Пупкин';
        $clientDto->firstName = 'Василий';
        $clientDto->middleName = 'Петрович';
        $clientDto->phoneCountry = 7;
        $clientDto->phoneCode = '920';
        $clientDto->phoneNumber = '00000001';

        $cellService = new CellService(
            $cells,
            new MemoryBaggageRepository(),
            new DummyDispatcher()
        );

        $cellService->loadBaggage($clientDto, $cellId->getId(), $startDate = new DateTimeImmutable, $daysCount = 42);

        $loadedCell = $cells->get($cellId);

        $this->assertNotNull($loadedCell->getBaggage());
        $this->assertNotNull($loadedCell->getPinCode());
        $this->assertNotNull($loadedCell->getBaggage()->getClient());
        $this->assertEquals($loadedCell->getStartDate(), $startDate);
        $this->assertEquals($loadedCell->getDaysCount(), $daysCount);

        $client = $loadedCell->getBaggage()->getClient();

        $this->assertEquals($client->getName()->getLast(), $clientDto->lastName);
        $this->assertEquals($client->getName()->getFirst(), $clientDto->firstName);
        $this->assertEquals($client->getName()->getMiddle(), $clientDto->middleName);

        $this->assertEquals($client->getPhone()->getCountry(), $clientDto->phoneCountry);
        $this->assertEquals($client->getPhone()->getCode(), $clientDto->phoneCode);
        $this->assertEquals($client->getPhone()->getNumber(), $clientDto->phoneNumber);
    }

}