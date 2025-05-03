<?php

namespace App\ValueObjects;

final readonly class BackgroundId
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
        if (empty($value)) {
            return null;
        }
        return new self($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(?BackgroundId $other): bool
    {
        if ($other === null) {
            return false;
        }
        return $this->value === $other->value;
    }
}
