<?php


namespace common\entities;


use common\entities\events\BaggageLoadedEvent;
use common\entities\events\BaggageUnloadedEvent;
use DateTimeImmutable;

class Cell implements AggregateRoot
{
    use EventTrait;

    private Id $id;
    private Cabinet $cabinet;
    private string $name;
    private string $address;

    private ?Id $baggageId;
    private ?DateTimeImmutable $startDate;
    private int $daysCount;

    private string $pinCode;


    /**
     * Cell constructor.
     * @param Id $id
     * @param Cabinet $cabinet
     * @param string $name
     * @param string $address
     */
    public function __construct(Id $id, Cabinet $cabinet, string $name, string $address)
    {
        $this->id = $id;
        $this->cabinet = $cabinet;
        $this->name = $name;
        $this->address = $address;
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

}