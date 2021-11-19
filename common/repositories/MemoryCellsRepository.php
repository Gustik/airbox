<?php
namespace common\repositories;

use common\entities\Cell;
use common\entities\Id;

class MemoryCellsRepository implements CellRepository
{
    private array $items = [];

    public function all(): array
    {
        return $this->items;
    }

    public function get(Id $id): Cell
    {
        if (!isset($this->items[$id->toString()])) {
            throw new NotFoundException('Cell not found.');
        }
        return clone $this->items[$id->toString()];
    }

    public function add(Cell $cell): void
    {
        $this->items[$cell->getId()->toString()] = $cell;
    }

    public function save(Cell $cell): void
    {
        $this->items[$cell->getId()->toString()] = $cell;
    }

    public function remove(Cell $cell): void
    {
        if ($this->items[$cell->getId()->toString()]) {
            unset($this->items[$cell->getId()->toString()]);
        }
    }
}