<?php
namespace common\dispatchers;

use Psr\EventDispatcher\EventDispatcherInterface;

class DummyDispatcher implements EventDispatcherInterface
{

    /**
     * @inheritDoc
     */
    public function dispatch(object $event)
    {
        \Yii::info('Dispatch event ' . \get_class($event));
    }
}