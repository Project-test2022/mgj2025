<?php

namespace App\ValueObjects;

final readonly class PlayerName
{
    private function __construct(public string $value)
    {
    }

    public static function from(string $value): self
    {
        return new self($value);
    }

    public static function tryFrom(?string $name): ?self
    {
        if (is_null($name)) {
            return null;
        }

        return new self($name);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
