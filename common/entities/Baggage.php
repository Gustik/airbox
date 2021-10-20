<?php


namespace common\entities;


use DateTimeImmutable;

class Baggage implements AggregateRoot
{
    use EventTrait;

    private Id $id;
    private DateTimeImmutable $date;
    private Client $client;
    private int $status;

    /**
     * Baggage constructor.
     * @param Id $id
     * @param DateTimeImmutable $date
     * @param Client $client
     */
    public function __construct(Id $id, DateTimeImmutable $date, Client $client)
    {
        $this->id = $id;
        $this->date = $date;
        $this->client = $client;
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

    public function getClient(): Client
    {
        return $this->client;
    }

    public function unload(): void
    {
        $this->status = BaggageStatus::Unloaded;
    }

}