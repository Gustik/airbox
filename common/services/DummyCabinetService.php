<?php


namespace common\services;


class DummyCabinetService implements ICabinetService
{
    public function openCell(string $cellAddress): bool
    {
        return true;
    }


    public function stateCell(string $cellAddress): array
    {
        return ['locked' => false, 'empty' => false];
    }
}