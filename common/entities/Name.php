<?php


namespace common\entities;


use Assert\Assertion;

class Name
{
    private string $last;
    private string $first;
    private string $middle;

    /**
     * Name constructor.
     * @param string $last
     * @param string $first
     * @param string|null $middle
     * @throws \Assert\AssertionFailedException
     */
    public function __construct(string $last, string $first, ?string $middle)
    {
        Assertion::notEmpty($last);
        Assertion::notEmpty($first);

        $this->last = $last;
        $this->first = $first;
        $this->middle = $middle;
    }

    public function getFull()
    {
        return trim($this->last . ' ' . $this->first . ' ' . $this->middle);
    }

    public function getFirst(): string { return $this->first; }
    public function getMiddle(): ?string { return $this->middle; }
    public function getLast(): string { return $this->last; }
}