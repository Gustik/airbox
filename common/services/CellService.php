<?php
namespace common\services;

use common\entities\Baggage;
use common\entities\Cell;
use common\entities\CellStatus;
use common\entities\Client;
use common\entities\Id;
use common\entities\Phone;
use common\repositories\BaggageRepository;
use common\repositories\CellRepository;
use common\services\dto\CreateCellDto;
use common\services\dto\CreateClientDto;
use DateTimeImmutable;
use Psr\EventDispatcher\EventDispatcherInterface;

class CellService
{
    private CellRepository $cells;
    private BaggageRepository $baggies;
    private EventDispatcherInterface $dispatcher;

    public function __construct(CellRepository $cells, BaggageRepository $baggies, EventDispatcherInterface $dispatcher)
    {
        $this->cells = $cells;
        $this->baggies = $baggies;
        $this->dispatcher = $dispatcher;
    }

    public function createCell(CreateCellDto $cellDto)
    {
        $cell = new Cell(
            $this->cellId = Id::next(),
            $cellName = $cellDto->cellName,
            $cellAddress = $cellDto->cellAddress,
            0,
            $cellDto->price,
        );
        $this->cells->add($cell);
        return $cell;
    }

    public function getCell(Id $id): Cell
    {
        return $this->cells->get($id);
    }

    public function deleteCell(Id $id)
    {
        $this->cells->remove($this->cells->get($id));
    }

    public function getBaggage(Id $id): Baggage
    {
        return $this->baggies->get($id);
    }

    /**
     *
     * Процедура загрузки багажа
     *
     * @param string $phone
     * @param Id $cellId
     * @param DateTimeImmutable $startDate
     * @param int $daysCount
     * @return Cell
     * @throws \Exception
     */
    public function loadBaggage(string $phone, Id $cellId, DateTimeImmutable $startDate, int $daysCount): Cell
    {
        /*
        == Процедура загрузки багажа
        Эта процедура является частью основного режима работы.
            • Клиент выбирает свободную ячейку.
            • Клиент выбирает срок хранения багажа (в сутках).
            • Клиент вводит контактные данные (контактный телефон).
            • Клиент вносит оплату (количество суток * стоимость за сутки).
            • Клиенту выдается чек, QR код на чеке является ключом к открытию ячейки, кроме QR кода на чеке должны быть время оплаты, длительность хранения, номер ячейки.
            • Электромагнитный замок открывается.
            • Клиент открывает дверцу и загружает свой багаж.
            • Клиент закрывает дверцу, электромагнитный замок запирается.
         */

        $baggage = new Baggage (
            $baggageId = Id::next(),
            $baggageDate = new \DateTimeImmutable(),
            $clientPhone = $phone
        );
        $this->baggies->add($baggage);

        $cell = $this->cells->get($cellId);
        if($cell->getStatus() !== CellStatus::Reserved) throw new \DomainException('Cell is not reserved');

        if(CabinetService::openCell($cell->getId()->toString())) {
            $cell->loadBaggage($baggage->getId(), $startDate, $daysCount);
        } else {
            throw new \DomainException('Cell is not opened');
        }

        $this->cells->save($cell);

        foreach ($cell->releaseEvents() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $cell;
    }

    public function unloadBaggage(Id $cellId)
    {
        /*
        == Процедура выгрузки багажа
        Эта процедура является частью основного режима работы.
            • Клиент подносит чек к считывателю QR кода или вводит пин код или использует специальную веб-ссылку.
            • В случае небольшой просрочки, система требует доплаты.
            • На экране показывается номер ячейки.
            • Электромагнитный замок открывается.
            • Клиент открывает дверцу и забирает свой багаж.
            • Багаж отмечается как отданный.
            • Ячейка отмечается как свободная.
            • Клиент закрывает дверцу, электромагнитный замок запирается.
         */
        $cell = $this->cells->get($cellId);

        $baggage = $this->baggies->get($cell->getBaggageId());
        $cell->unloadBaggage();
        $this->cells->save($cell);

        $baggage->unload();
        $this->baggies->save($baggage);

        foreach ($cell->releaseEvents() as $event) {
            $this->dispatcher->dispatch($event);
        }
    }

    public function unloadForgottenBaggage(Baggage $baggage)
    {
        /*
        == Процедура выгрузки забытого багажа
        • Система информирует диспетчерскую службу, что есть забытый багаж, время просрочки регламентируется внутренними правилами аэропорта.
        • Служащий аэропорта открывает ячейку из диспетчерской или прямо с терминала.
        • Служащий отмечает на багаже время загрузки багажа, номер ячейки и длительность хранения.
        • Служащий переносит багаж в склад забытого багажа
        • Система отмечает ячейку как свободную.
        • Код на ранее выданном чеке отмечается как унесенный на склад забытого багажа.
        • Служба аэропорта пытается найти владельца багажа.
        • Если владелец пришел сам:
            ◦ Клиент подносит чек к считывателю QR кода.
            ◦ Терминал сообщает, что багаж в складе забытого багажа.
            ◦ Клиент показывает чек служащему -> получает багаж.
        • Клиент не показывает чек (потерян, испорчен) -> Служба аэропорта действует по обычной инструкции для утерянного багажа.
        • Если владелец улетел без багажа:
            ◦ Служба аэропорта передает груз авиакомпании для дальнейшей передачи багажа пассажиру.
         */
        return false;
    }

    public function baggageInspection(Baggage $baggage)
    {
        /* Система должна иметь режим работы для служб безопасности */
        return false;
    }

    /**
     * @param Cell $cell
     * @return Cell
     * @throws \DomainException
     */
    public function reserveCell(Cell $cell): Cell
    {
        if($cell->isReserved()) {
            throw new \DomainException('Cell is all ready reserved.');
        }
        $cell->reserve();
        $this->cells->save($cell);
        foreach ($cell->releaseEvents() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $cell;
    }

    public function cellListDto(): array
    {
        $cells = [];

        foreach ($this->cells->all() as $cell) {
            $cellDto = new CreateCellDto();
            $cellDto->cellId = $cell->getId()->toString();
            $cellDto->cellName = $cell->getName();
            $cellDto->cellAddress = $cell->getAddress();
            $cellDto->status = $cell->getStatus();
            $cellDto->busy = $cell->isBusy();
            $cellDto->price = $cell->getPrice();
            $cells[] = $cellDto;
        }
        return $cells;
    }
}