<?php

namespace App\ValueObjects;

final readonly class SelectRate
{
    private function __construct(public string $value)
    {
    }

    public static function from(string $value): self
    {
        return new self($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
