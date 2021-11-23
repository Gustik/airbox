<?php


namespace common\entities;


use DateTimeImmutable;

class Baggage implements AggregateRoot
{
    use EventTrait;

    private Id $id;
    private DateTimeImmutable $date;
    private int $status;
    private string $phone;

    /**
     * Baggage constructor.
     * @param Id $id
     * @param DateTimeImmutable $date
     * @param string $phone
     */
    public function __construct(Id $id, DateTimeImmutable $date, string $phone)
    {
        $this->id = $id;
        $this->date = $date;
        $this->phone = $phone;
        $this->status = BaggageStatus::Loaded;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function unload(): void
    {
        $this->status = BaggageStatus::Unloaded;
    }

    public function isUnloaded(): bool
    {
        return $this->status === BaggageStatus::Unloaded;
    }

}