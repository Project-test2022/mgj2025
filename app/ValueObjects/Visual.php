<?php

namespace App\ValueObjects;

final readonly class Visual
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

    public function sub(Visual $other): self
    {
        return new self($this->value - $other->value);
    }
}
