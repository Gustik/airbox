<?php
namespace common\services\dto;

use common\entities\Id;
use DateTimeImmutable;

class LoadBaggageDto
{
    public Id $id;
    public DateTimeImmutable $date;

}