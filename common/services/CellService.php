<?php
namespace common\services;

use common\entities\Baggage;
use common\entities\Cell;
use common\entities\Client;
use common\entities\Id;
use common\entities\Name;
use common\entities\Phone;
use common\repositories\BaggageRepository;
use common\repositories\CellRepository;
use common\services\dto\CreateClientDto;
use common\services\dto\LoadBaggageDto;
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

    /**
     *
     * Процедура загрузки багажа
     *
     * @param CreateClientDto $clientDto
     * @param string $cellId
     * @param DateTimeImmutable $startDate
     * @param int $daysCount
     * @return Cell
     * @throws \Assert\AssertionFailedException
     */
    public function loadBaggage(CreateClientDto $clientDto, string $cellId, DateTimeImmutable $startDate, int $daysCount): Cell
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
            $client = new Client(
                $clientId = Id::next(),
                $clientDate = new \DateTimeImmutable(),
                $clientName = new Name($clientDto->lastName, $clientDto->firstName, $clientDto->middleName),
                $clientPhone = new Phone($clientDto->phoneCountry, $clientDto->phoneCode, $clientDto->phoneNumber),
            )
        );
        $this->baggies->add($baggage);

        $cell = $this->cells->get(new Id($cellId));
        $cell->loadBaggage($baggage, $startDate, $daysCount);

        $this->cells->save($cell);

        foreach ($cell->releaseEvents() as $event) {
            $this->dispatcher->dispatch($event);
        }

        return $cell;
    }

    public function unloadBaggage(string $cellId)
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
        $cell = $this->cells->get(new Id($cellId));
        $cell->unloadBaggage();

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
}