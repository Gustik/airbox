<?php


namespace common\entities;


use Assert\Assertion;

class Phone
{
    private int $country;
    private string $code;
    private string $number;

    /**
     * Phone constructor.
     * @param int $country
     * @param string $code
     * @param string $number
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(int $country, string $code, string $number)
    {
        Assertion::notEmpty($country);
        Assertion::notEmpty($code);
        Assertion::notEmpty($number);
        $this->country = $country;
        $this->code = $code;
        $this->number = $number;
    }

    public function isEqualTo(self $phone): bool
    {
        return $this->getFull() === $phone->getFull();
    }

    public function getFull(): string
    {
        return '+' . $this->country . ' (' . $this->code . ') ' . $this->number;
    }

    public function getCountry(): int { return $this->country; }
    public function getCode(): string { return $this->code; }
    public function getNumber(): string { return $this->number; }

}