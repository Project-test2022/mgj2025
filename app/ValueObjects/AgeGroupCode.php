<?php

namespace App\ValueObjects;

final readonly class AgeGroupCode
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

    public function equals(?AgeGroupCode $other): bool
    {
        if ($other === null) {
            return false;
        }
        return $this->value === $other->value;
    }
}
