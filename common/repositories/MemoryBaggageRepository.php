<?php
namespace common\repositories;

use common\entities\Baggage;
use common\entities\Id;

class MemoryBaggageRepository implements BaggageRepository
{
    private $items = [];

    public function get(Id $id): Baggage
    {
        if (!isset($this->items[$id->getId()])) {
            throw new NotFoundException('Baggage not found.');
        }
        return clone $this->items[$id->getId()];
    }

    public function add(Baggage $baggage): void
    {
        $this->items[$baggage->getId()->getId()] = $baggage;
    }

    public function save(Baggage $baggage): void
    {
        $this->items[$baggage->getId()->getId()] = $baggage;
    }

    public function remove(Baggage $baggage): void
    {
        if ($this->items[$baggage->getId()->getId()]) {
            unset($this->items[$baggage->getId()->getId()]);
        }
    }
}