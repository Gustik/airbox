<?php
namespace common\repositories;

use common\entities\Cell;
use common\entities\Id;

class MemoryCellsRepository implements CellRepository
{
    private $items = [];

    public function get(Id $id): Cell
    {
        if (!isset($this->items[$id->getId()])) {
            throw new NotFoundException('Cell not found.');
        }
        return clone $this->items[$id->getId()];
    }

    public function add(Cell $cell): void
    {
        $this->items[$cell->getId()->getId()] = $cell;
    }

    public function save(Cell $cell): void
    {
        $this->items[$cell->getId()->getId()] = $cell;
    }

    public function remove(Cell $cell): void
    {
        if ($this->items[$cell->getId()->getId()]) {
            unset($this->items[$cell->getId()->getId()]);
        }
    }
}