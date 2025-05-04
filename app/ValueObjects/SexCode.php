<?php

namespace App\ValueObjects;

final readonly class SexCode
{
    private function __construct(public string $value)
    {
    }

    public static function from(string $value): self
    {
        return new self($value);
    }

    public static function tryFrom(?string $value): ?self
    {
        if (is_null($value)) {
            return null;
        }

        return new self($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
