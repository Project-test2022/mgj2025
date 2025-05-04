<?php

namespace App\ValueObjects;

final readonly class Income
{
    private function __construct(public int $value)
    {
    }

    public static function from(string $value): self
    {
        return new self((int)$value);
    }

    public static function tryFrom(?string $value): ?self
    {
        if (is_null($value)) {
            return null;
        }

        return self::from($value);
    }

    public function __toString(): string
    {
        return $this->value;
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

    public function sub(Income $other): self
    {
        return new self($this->value - $other->value);
    }

    public function add(Income $other): self
    {
        return new self($this->value + $other->value);
    }
}
