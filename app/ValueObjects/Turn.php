<?php

namespace App\ValueObjects;

final readonly class Turn
{
    private function __construct(public string $value)
    {
    }

    public static function from(string $value): self
    {
        return new self($value);
    }
}
