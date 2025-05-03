<?php

namespace App\Entities;

use App\ValueObjects\BackgroundId;
use App\ValueObjects\BackgroundName;

final readonly class Background
{
    public function __construct(
        public BackgroundId $id,
        public BackgroundName $name,
        public string $image,
    ) {
    }
}
