<?php


namespace common\entities;


abstract class CellStatus
{
    const Unlocked = 0;
    const Locked = 1;
    const Reserved = 2;
}