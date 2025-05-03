<?php

namespace App\Entities;

use App\ValueObjects\AgeGroupCode;
use App\ValueObjects\AgeGroupName;

final readonly class AgeGroup
{
    public function __construct(
        public AgeGroupCode $code,
        public AgeGroupName $name,
    ) {
    }
}
