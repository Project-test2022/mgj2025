<?php

namespace App\ValueObjects;

final readonly class Turn
{
    private function __construct(public int $value)
    {
    }

    public static function from(string $value): self
    {
        return new self((int)$value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function next(): self
    {
        return new self($this->value + 1);
    }
}
