<?php
/* @var $this yii\web\View */
/* @var $cells array */

$this->title = 'Ячейки';
$this->params['breadcrumbs'][] = $this->title;

use yii\helpers\Html;
use yii\helpers\Url; ?>
<div class="cell-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="cells-container">
    <?php foreach ($cells as $cell): ?>
        <a href="<?= Url::toRoute(['view', 'id' => $cell->cellId]) ?>">
            <div class="<?=$cell->busy ? 'cell busyCell' : 'cell freeCell'?>">
                <?=$cell->cellName?>
            </div>
        </a>
    <?php endforeach; ?>
    </div>
</div>

<style>
    .cells-container {
        display: flex;
        flex-wrap: wrap;
        flex-direction: row;
    }
    .cell {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        margin: 10px;
        color: white;
    }
    .busyCell {
        background: red;
    }

    .freeCell {
        background: green;
    }
</style>
