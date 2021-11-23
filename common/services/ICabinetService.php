<?php


namespace common\services;


interface ICabinetService
{
    static function openCell(string $cellAddress): bool;
    static function stateCell(string $cellAddress): array;
}