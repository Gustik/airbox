<?php

namespace common\tests\unit\repositories;


use common\repositories\Hydrator;
use common\repositories\SqlBaggageRepository;
use common\tests\_fixtures\BaggageFixture;


class SqlBaggageRepositoryTest extends BaseBaggageRepositoryTest
{
    /**
     * @var \common\tests\UnitTester
     */
    public $tester;

    public function _before()
    {
        $this->tester->haveFixtures([
            'baggage' => BaggageFixture::class,
        ]);

        $this->repository = new SqlBaggageRepository(
            \Yii::$app->db,
            new Hydrator(),
        );
    }
}