<?php


namespace common\entities;


use DateTimeImmutable;

class Client
{
    private Id $id;
    private DateTimeImmutable $date;
    private Phone $phone;

    /**
     * Client constructor.
     * @param Id $id
     * @param DateTimeImmutable $date
     * @param Phone $phone
     */
    public function __construct(Id $id, DateTimeImmutable $date, Phone $phone)
    {
        $this->id = $id;
        $this->date = $date;
        $this->phone = $phone;
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }


    /**
     * @return Phone
     */
    public function getPhone(): Phone
    {
        return $this->phone;
    }


}