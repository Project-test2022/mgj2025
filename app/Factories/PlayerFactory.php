<?php

namespace App\Factories;

use App\Entities\Player;
use App\ValueObjects\PlayerId;

final readonly class PlayerFactory
{
    public function create(): Player
    {
        $id = PlayerId::from(uniqid()); // TODO: Generate a unique ID
        return new Player(
            $id,
        );
    }
}
