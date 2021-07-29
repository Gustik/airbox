<?php
namespace common\entities\events;

use common\entities\Id;

class BaggageLoadedEvent
{
    public Id $cellId;
    public string $pinCode;

    public function __construct(Id $cellId, string $pinCode)
    {
        $this->cellId = $cellId;
        $this->pinCode = $pinCode;
    }

}