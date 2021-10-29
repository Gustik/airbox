<?php

namespace common\tests\unit\repositories;


use common\repositories\Hydrator;
use common\repositories\SqlCellsRepository;
use common\tests\_fixtures\CellFixture;


class SqlCellsRepositoryTest extends BaseRepositoryTest
{
    /**
     * @var \common\tests\UnitTester
     */
    public $tester;

    public function _before()
    {
        $this->tester->haveFixtures([
            'cell' => CellFixture::class,
        ]);

        $this->repository = new SqlCellsRepository(
            \Yii::$app->db,
            new Hydrator(),
        );
    }
}