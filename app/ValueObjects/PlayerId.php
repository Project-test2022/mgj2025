<?php

namespace App\ValueObjects;

final readonly class PlayerId
{
    private function __construct(public string $id)
    {
    }

    public static function from(string $id): self
    {
        return new self($id);
    }
}
