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

        $model->turn = $player->turn()->value;
        $model->birth = $player->birthYear->value;
        $model->sex = $player->sex->value;
        $model->money = $player->money->value;
        $model->name = $player->name->value;
        $model->health = $player->health->value;
        $model->a_intelligence = $player->ability->intelligence->value;
        $model->a_sport = $player->ability->sport->value;
        $model->e_business = $player->evaluation->business->value;
        $model->e_love = $player->evaluation->love->value;

        return $model;
    }
}
