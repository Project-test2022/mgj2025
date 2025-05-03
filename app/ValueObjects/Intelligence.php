<?php

namespace App\ValueObjects;

final readonly class Intelligence
{
    private function __construct(public string $value)
    {
    }

    public static function from(string $value): self
    {
        return new self($value);
    }
}
