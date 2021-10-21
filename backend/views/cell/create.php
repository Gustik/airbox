<?php
/* @var $this yii\web\View */
/* @var $errors array */

$this->title = 'Добавление новой ячейки';
$this->params['breadcrumbs'][] = ['label' => 'Ячейки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

use yii\helpers\Html; ?>
<div class="cell-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="cell-form">
        <?php var_dump($errors) ?>
        <?= Html::beginForm(['create'], 'POST'); ?>
        <?= Html::input('text', 'cellName') ?>
        <?= Html::input('text', 'cellAddress') ?>
        <?= Html::input('text', 'price') ?>

        <div class="form-group">
            <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']); ?>
        </div>
        <?= Html::endForm(); ?>

    </div>

</div>