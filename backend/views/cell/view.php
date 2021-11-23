<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $cell \common\services\dto\CreateCellDto */

$this->title = 'Ячейка №' . $cell->cellName;
$this->params['breadcrumbs'][] = ['label' => 'Ячейки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cell-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Удалить', ['delete', 'id' => $cell->cellId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены что хотите удалить эту ячейку?',
                'method' => 'post',
            ],
        ]) ?>

        <?= Html::a('Освободить', ['release', 'id' => $cell->cellId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены что хотите освободить эту ячейку?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div>
        Номер ячейки: <?=$cell->cellName?><br>
        Адрес ячейки: <?=$cell->cellAddress?><br>
        Статус: <?=\common\entities\CellStatus::name($cell->status)?><br>
        Цена: <?=$cell->price?><br>
    </div>

</div>
