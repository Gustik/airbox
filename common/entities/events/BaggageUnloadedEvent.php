<?php
namespace common\entities\events;

use common\entities\Id;

class BaggageUnloadedEvent
{
    public Id $cellId;

    public function __construct(Id $cellId)
    {
        $this->cellId = $cellId;
    }

}