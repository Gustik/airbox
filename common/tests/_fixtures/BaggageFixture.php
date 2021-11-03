<?php
namespace common\tests\_fixtures;

use yii\test\ActiveFixture;

class BaggageFixture extends ActiveFixture
{
    public $tableName = '{{%baggage}}';
    public $dataFile = '@common/tests/_data/baggage.php';

}