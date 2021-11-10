<?php

use common\dispatchers\DummyDispatcher;
use common\repositories\BaggageRepository;
use common\repositories\CellRepository;
use common\repositories\Hydrator;
use common\repositories\SqlBaggageRepository;
use common\repositories\SqlCellsRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use yii\di\Instance;

Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::$container->setSingleton(Hydrator::class);
Yii::$container->setSingleton('db', function () {
    return Yii::$app->db;
});
Yii::$container->setSingleton(CellRepository::class, SqlCellsRepository::class, [Instance::of('db')]);
Yii::$container->setSingleton(BaggageRepository::class, SqlBaggageRepository::class, [Instance::of('db')]);
Yii::$container->setSingleton(EventDispatcherInterface::class, DummyDispatcher::class);