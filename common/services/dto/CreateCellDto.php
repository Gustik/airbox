<?php


namespace common\services\dto;


class CreateCellDto
{
    public string $cellId;
    public string $cellName;
    public string $cellAddress;
    public int $status;
    public bool $busy;
    public int $price;
    public int $daysCount;
}