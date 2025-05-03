<?php

namespace App\ValueObjects;

use Carbon\Carbon;

final readonly class BirthYear
{
    private function __construct(public string $value)
    {
    }

    public static function from(string $value): self
    {
        return new self($value);
    }

    public function toDate(): Carbon
    {
        return Carbon::createFromFormat('Y', $this->value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
