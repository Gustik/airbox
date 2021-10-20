<?php


namespace common\services;


interface ICabinetService
{
    public function openCell(string $cellAddress): bool;
    public function stateCell(string $cellAddress): array;
}