<?php
namespace common\repositories;

use common\entities\Baggage;
use common\entities\Id;

interface BaggageRepository
{
    public function get(Id $id): Baggage;
    public function add(Baggage $baggage): void;
    public function save(Baggage $baggage): void;
    public function remove(Baggage $baggage): void;
}