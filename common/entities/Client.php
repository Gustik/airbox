<?php


namespace common\entities;


use DateTimeImmutable;

class Client
{
    private Id $id;
    private DateTimeImmutable $date;
    private Name $name;
    private Phone $phone;

    /**
     * Client constructor.
     * @param Id $id
     * @param DateTimeImmutable $date
     * @param Name $name
     * @param Phone $phone
     */
    public function __construct(Id $id, DateTimeImmutable $date, Name $name, Phone $phone)
    {
        $this->id = $id;
        $this->date = $date;
        $this->name = $name;
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
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }

    /**
     * @return Phone
     */
    public function getPhone(): Phone
    {
        return $this->phone;
    }


}