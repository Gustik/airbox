<?php


namespace common\entities;


abstract class BaggageStatus
{
    const Loaded = 0; // Загружен в ячейку
    const Unloaded = 1; // Забрали
    const OnStorehouse = 2; // На складе забытых багажей
}