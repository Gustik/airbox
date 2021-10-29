<?php
namespace common\tests\_fixtures;

use yii\test\ActiveFixture;

class CellFixture extends ActiveFixture
{
    public $tableName = '{{%cell}}';
    public $dataFile = '@common/tests/_data/cell.php';

}