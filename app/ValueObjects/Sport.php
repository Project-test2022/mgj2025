<?php

namespace App\ValueObjects;

final readonly class Sport
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

    public function sub(Sport $other): self
    {
        return new self($this->value - $other->value);
    }

    public function add(Sport $other): self
    {
        $value = $this->value + $other->value;
        if ($value < 0) {
            $value = 0;
        } elseif ($value > 100) {
            $value = 100;
        }
        return new self($value);
    }
}
