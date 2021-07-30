<?php


namespace common\services;


class DummyCabinetService implements ICabinetService
{
    public function openCell(string $cabinetAddress, string $cellAddress): bool
    {
        return true;
    }

    public function closeCell(string $cabinetAddress, string $cellAddress): bool
    {
        return true;
    }
}