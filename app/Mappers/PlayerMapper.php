<?php

namespace App\Mappers;

use App\Entities\Player;
use App\Models\PlayerModel;

final readonly class PlayerMapper
{
    public function toModel(Player $player): PlayerModel
    {
        $model = PlayerModel::find($player->id);
        if (!$model) {
            $model = new PlayerModel();
        }

        // TODO: Map properties from Player to PlayerModel

        return $model;
    }
}
