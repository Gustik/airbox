<?php


namespace common\entities;


class CellStatus
{
    const Unlocked = 0;
    const Locked = 1;
    const Reserved = 2;

    static function name($status): string
    {
        switch ($status) {
            case static::Unlocked:
                return 'Открыт';
            case static::Locked:
                return 'Закрыт';
            case static::Reserved:
                return 'Зарезервирован';
        }
        return 'Неизвестный статус';
    }
}