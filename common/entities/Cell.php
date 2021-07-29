<?php


namespace common\entities;


use common\entities\events\BaggageLoadedEvent;
use DateTimeImmutable;

class Cell implements AggregateRoot
{
    use EventTrait;

    private Id $id;
    private Cabinet $cabinet;
    private string $name;
    private string $address;

    private Baggage $baggage;
    private DateTimeImmutable $startDate;
    private int $daysCount;

    public string $pinCode;


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

    public function getBaggage(): Baggage
    {
        return $this->baggage;
    }

    public function loadBaggage(Baggage $baggage, DateTimeImmutable $startDate, int $daysCount): Cell
    {
        $this->baggage = $baggage;
        $this->startDate = $startDate;
        $this->daysCount = $daysCount;
        $this->pinCode = uniqid();

        $this->recordEvent(new BaggageLoadedEvent($this->id, $this->pinCode));

        return $this;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function getDaysCount()
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

    public function getId()
    {
        return $this->id;
    }

}