<?php

namespace App\Entities;

use App\ValueObjects\PlayerId;

final readonly class Player
{
    public function __construct(
        public PlayerId $id,
    ) {
    }
}
