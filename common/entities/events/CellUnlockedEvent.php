<?php


namespace common\entities\events;


use common\entities\Id;

class CellUnlockedEvent
{
    public Id $cellId;

    public function __construct(Id $id)
    {
        $this->cellId = $id;
    }
}