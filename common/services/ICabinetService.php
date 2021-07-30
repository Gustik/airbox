<?php


namespace common\services;


interface ICabinetService
{
    public function openCell(string $cabinetAddress, string $cellAddress): bool;
    public function closeCell(string $cabinetAddress, string $cellAddress): bool;
}