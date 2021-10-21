<?php
namespace common\repositories;

use common\entities\Cell;
use common\entities\Id;

interface CellRepository
{
    public function all(): array;
    public function get(Id $id): Cell;
    public function add(Cell $cell): void;
    public function save(Cell $cell): void;
    public function remove(Cell $cell): void;
}