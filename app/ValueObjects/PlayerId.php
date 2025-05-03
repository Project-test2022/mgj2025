<?php

namespace App\ValueObjects;

final readonly class PlayerId
{
    public function __construct(public string $id)
    {
    }
}
