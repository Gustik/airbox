<?php


namespace common\services;


class CabinetService implements ICabinetService
{
    static function openCell(string $cellAddress): bool
    {
        return true;
    }


    static function stateCell(string $cellAddress): array
    {
        return ['locked' => false, 'empty' => false];
    }
}