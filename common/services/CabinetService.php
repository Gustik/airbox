<?php


namespace common\services;


class CabinetService implements ICabinetService
{
    static function openCell(string $cellAddress): bool
    {
        /*$res = file_get_contents('http://modbus/');
        $cell = json_decode($res);
        return ($cell->OPENED == 1);*/
        return true;
    }


    static function stateCell(string $cellAddress): array
    {
        return ['locked' => false, 'empty' => false];
    }
}