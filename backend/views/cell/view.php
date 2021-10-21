<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $cell \common\services\dto\CreateCellDto */

$this->title = $cell->cellName;
$this->params['breadcrumbs'][] = ['label' => 'Ячейки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cell-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div>
        <?=$cell->cellName?><br>
        <?=$cell->cellAddress?><br>
    </div>

</div>
