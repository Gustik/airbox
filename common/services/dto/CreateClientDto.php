<?php


namespace common\services\dto;


class CreateClientDto
{
    public string $firstName;
    public string $lastName;
    public string $middleName;
    public int $phoneCountry;
    public string $phoneCode;
    public string $phoneNumber;
}