<?php

namespace App\ValueObjects;

final readonly class Money
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

    public function sub(Money $other): self
    {
        return new self($this->value - $other->value);
    }

    public function add(Money $other): self
    {
        return new self($this->value + $other->value);
    }

    public function format(): string
    {
        $value = number_format((abs($this->value)));
        if ($this->value < 0) {
            return '-¥' . $value;
        } else {
            return '¥' . $value;
        }
    }

    public function addIncome(Income $income): self
    {
        return new self($this->value + $income->value);
    }
}
