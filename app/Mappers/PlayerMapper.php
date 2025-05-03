<?php

namespace App\Mappers;

use App\Entities\Player;
use App\Models\PlayerModel;
use App\Models\PlayerView;
use App\Models\SexModel;
use App\ValueObjects\Ability;
use App\ValueObjects\BackgroundImageId;
use App\ValueObjects\BirthYear;
use App\ValueObjects\Business;
use App\ValueObjects\Evaluation;
use App\ValueObjects\Health;
use App\ValueObjects\Intelligence;
use App\ValueObjects\Love;
use App\ValueObjects\Money;
use App\ValueObjects\PlayerFaceId;
use App\ValueObjects\PlayerId;
use App\ValueObjects\PlayerName;
use App\ValueObjects\SexName;
use App\ValueObjects\Sport;
use App\ValueObjects\Turn;
use App\ValueObjects\Visual;

final readonly class PlayerMapper
{
    public function toModel(Player $player): PlayerModel
    {
        $model = PlayerModel::query()->find($player->id);
        if (!$model) {
            $model = new PlayerModel();
            $model->player_id = $player->id->value;
        }
        $sex = SexModel::query()->where('sex_nm', $player->sexName->value)->first();

        $model->turn = $player->turn->value;
        $model->player_name = $player->name->value;
        $model->sex_cd = $sex->sex_cd;
        $model->birth_date = $player->birthYear->toDate()->format('Y-m-d');
        $model->total_money = $player->totalMoney->value;
        $model->health = $player->health->value;
        $model->a_intelligence = $player->ability->intelligence->value;
        $model->a_sport = $player->ability->sport->value;
        $model->a_visual = $player->ability->visual->value;
        $model->e_business = $player->evaluation->business->value;
        $model->e_love = $player->evaluation->love->value;
        $model->bg_id = $player->backgroundImageId?->value;
        $model->player_face_id = $player->playerFaceId?->value;

        return $model;
    }

    public function toEntity(PlayerView $view): Player
    {
        return new Player(
            PlayerId::from($view->player_id),
            PlayerName::from($view->player_name),
            SexName::from($view->sex_nm),
            BirthYear::from($view->birth_year),
            Turn::from($view->turn),
            Money::from($view->total_money),
            Health::from($view->health),
            Ability::from(
                Intelligence::from($view->a_intelligence),
                Sport::from($view->a_sport),
                Visual::from($view->a_visual),
            ),
            Evaluation::from(
                Business::from($view->e_business),
                Love::from($view->e_love),
            ),
            BackgroundImageId::tryFrom($view->bg_id),
            PlayerFaceId::tryFrom($view->player_face_id),
        );
    }
}
