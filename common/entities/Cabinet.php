<?php


namespace common\entities;


class Cabinet
{
    private Id $id;
    private string $name;
    private string $address;

    /**
     * Cabinet constructor.
     * @param Id $id
     * @param string $name
     * @param string $address
     */
    public function __construct(Id $id, string $name, string $address)
    {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
    }

}