<?php

namespace common\tests\unit\repositories;


use common\repositories\MemoryCellsRepository;


class MemoryCellsRepositoryTest extends BaseCellsRepositoryTest
{
    /**
     * @var \common\tests\UnitTester
     */
    public $tester;

    public function _before()
    {
        $this->repository = new MemoryCellsRepository();
    }
}