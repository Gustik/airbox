<?php

namespace common\tests\unit\repositories;


use common\repositories\MemoryBaggageRepository;


class MemoryBaggageRepositoryTest extends BaseBaggageRepositoryTest
{
    /**
     * @var \common\tests\UnitTester
     */
    public $tester;

    public function _before()
    {
        $this->repository = new MemoryBaggageRepository();
    }
}