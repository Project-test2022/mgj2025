<?php

namespace App\Entities;

use App\ValueObjects\AgeGroupCode;
use App\ValueObjects\PlayerFaceId;
use App\ValueObjects\PlayerId;

final readonly class PlayerFace
{
    public function __construct(
        public PlayerFaceId $id,
        public PlayerId $playerId,
        public AgeGroupCode $ageGroupCode,
        public string $image,
    ) {
    }
}
