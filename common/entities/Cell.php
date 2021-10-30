<?php


namespace common\entities;


use Assert\Assertion;
use common\entities\events\BaggageLoadedEvent;
use common\entities\events\BaggageUnloadedEvent;
use common\entities\events\CellLockedEvent;
use common\entities\events\CellReservedEvent;
use common\entities\events\CellUnlockedEvent;
use common\services\dto\CreateCellDto;
use DateTimeImmutable;

class Cell implements AggregateRoot
{
    use EventTrait;

    private Id $id;
    private string $name;
    private string $address;
    private int $status;

    private ?Id $baggageId;
    private ?DateTimeImmutable $startDate;
    private int $daysCount;
    private int $price;

    private string $pinCode;


    /**
     * Cell constructor.
     * @param Id $id
     * @param string $name
     * @param string $address
     * @param int $daysCount
     * @param int $price
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(Id $id, string $name, string $address, int $daysCount, int $price)
    {
        Assertion::notEmpty($name, "Имя ячейки не должно быть пустым");
        Assertion::notEmpty($address, "Адрес ячейки не должен быть пустым");
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->status = CellStatus::Locked;
        $this->daysCount = $daysCount;
        $this->price = $price;
        $this->baggageId = null;
        $this->startDate = null;
        $this->pinCode = '';
    }

    public function dto(): CreateCellDto
    {
        $dto = new CreateCellDto();
        $dto->cellId = $this->getId()->getId();
        $dto->cellName = $this->getName();
        $dto->cellAddress = $this->getAddress();
        $dto->price = $this->getPrice();
        $dto->daysCount = $this->getDaysCount();
        $dto->status = $this->getStatus();
        $dto->busy = $this->isBusy();
        return $dto;
    }

    public function getBaggageId(): ?Id
    {
        return $this->baggageId;
    }

    public function loadBaggage(Id $baggageId, DateTimeImmutable $startDate, int $daysCount): Cell
    {
        $this->baggageId = $baggageId;
        $this->startDate = $startDate;
        $this->daysCount = $daysCount;
        $this->pinCode = uniqid();

        $this->recordEvent(new BaggageLoadedEvent($this->id, $this->pinCode));

        return $this;
    }

    public function unloadBaggage()
    {
        $this->baggageId = null;
        $this->startDate = null;
        $this->daysCount = 0;
        $this->price = 0;
        $this->pinCode = '';
        $this->recordEvent(new BaggageUnloadedEvent($this->id));
    }

    public function isBaggageLoaded(): bool
    {
        return $this->baggageId ? true : false;
    }

    public function getStartDate(): ?DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getDaysCount(): int
    {
        return $this->daysCount;
    }

    /**
     * @return string
     */
    public function getPinCode(): string
    {
        return $this->pinCode;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getStatus(): int
    {
        return $this->status;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }


    public function lock(): Cell
    {
        $this->status = CellStatus::Locked;
        $this->recordEvent(new CellLockedEvent($this->id));
        return $this;
    }

    public function unlock(): Cell
    {
        $this->status = CellStatus::Unlocked;
        $this->recordEvent(new CellUnlockedEvent($this->id));
        return $this;
    }

    public function reserve(): Cell
    {
        $this->status = CellStatus::Reserved;
        $this->recordEvent(new CellReservedEvent($this->id));
        return $this;
    }

    public function isReserved(): bool
    {
        return $this->status === CellStatus::Reserved;
    }

    public function isLocked(): bool
    {
        return $this->status === CellStatus::Locked;
    }

    public function isUnlocked(): bool
    {
        return $this->status === CellStatus::Unlocked;
    }

    public function isBusy(): bool
    {
        return $this->isUnlocked() || $this->isReserved();
    }
}