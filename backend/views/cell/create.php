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
        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger">
            <?= $error ?>
            </div>
        <?php endforeach ?>
        <?= Html::beginForm(['create'], 'POST'); ?>

        <?= Html::input('text', 'cellName', null, ['class' => 'form-control', 'placeholder'=>"Имя ячейки"]) ?>
        <?= Html::input('text', 'cellAddress', null, ['class' => 'form-control', 'placeholder'=>"Адрес ячейки"]) ?>
        <?= Html::input('text', 'price', null, ['class' => 'form-control', 'placeholder'=>"Цена за сутки"]) ?>

        <div class="form-group">
            <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']); ?>
        </div>
        <?= Html::endForm(); ?>

    </div>

</div>