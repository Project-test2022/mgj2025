<?php

namespace App\ValueObjects;

final readonly class Sense
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

    public function sub(Sense $from): self
    {
        return new self($this->value - $from->value);
    }

    public function add(Sense $other): self
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
