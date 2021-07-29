<?php


namespace common\entities;


interface AggregateRoot
{
    /**
     * @return array
     */
    public function releaseEvents(): array;
}