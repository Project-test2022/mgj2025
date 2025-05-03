<?php

namespace App\ValueObjects;

final readonly class Health
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

    public function sub(Health $other): self
    {
        return new self($this->value - $other->value);
    }

    public function add(Health $health): self
    {
        $value = $this->value + $health->value;
        if ($value < 0) {
            $value = 0;
        } elseif ($value > 100) {
            $value = 100;
        }
        return new self($value);
    }
}
