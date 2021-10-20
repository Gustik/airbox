<?php

use common\dispatchers\DummyDispatcher;
use common\repositories\BaggageRepository;
use common\repositories\CellRepository;
use common\repositories\MemoryBaggageRepository;
use common\repositories\MemoryCellsRepository;
use Psr\EventDispatcher\EventDispatcherInterface;

Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::$container->set(CellRepository::class, MemoryCellsRepository::class);
Yii::$container->set(BaggageRepository::class, MemoryBaggageRepository::class);
Yii::$container->set(EventDispatcherInterface::class, DummyDispatcher::class);