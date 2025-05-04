<?php

namespace App\ValueObjects;

final readonly class Job
{
    private function __construct(public string $value)
    {
    }

    public static function from(string $value): self
    {
        return new self($value);
    }

    public static function tryFrom(?string $other): ?self
    {
        if (empty($other)) {
            return null;
        }

        return self::from($other);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(?Job $other): bool
    {
        if ($other === null) {
            return false;
        }

        return $this->value === $other->value;
    }
}
