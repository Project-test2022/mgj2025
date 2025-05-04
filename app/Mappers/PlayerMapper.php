<?php

namespace App\Mappers;

use App\Entities\Player;
use App\Models\PlayerModel;
use App\Models\PlayerView;
use App\Models\SexModel;
use App\ValueObjects\Ability;
use App\ValueObjects\AgeGroupCode;
use App\ValueObjects\BackgroundId;
use App\ValueObjects\BirthYear;
use App\ValueObjects\Business;
use App\ValueObjects\Evaluation;
use App\ValueObjects\Health;
use App\ValueObjects\Income;
use App\ValueObjects\Intelligence;
use App\ValueObjects\Happiness;
use App\ValueObjects\Job;
use App\ValueObjects\Money;
use App\ValueObjects\Partner;
use App\ValueObjects\PlayerFaceId;
use App\ValueObjects\PlayerId;
use App\ValueObjects\PlayerName;
use App\ValueObjects\Sense;
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
        $model->a_sense = $player->ability->sense->value;
        $model->e_business = $player->evaluation->business->value;
        $model->e_happiness = $player->evaluation->happiness->value;
        $model->bg_id = $player->backgroundId?->value;
        $model->player_face_id = $player->playerFaceId?->value;
        $model->age_grp_cd = $player->ageGroupCode?->value;
        $model->job = $player->job?->value;
        $model->income = $player->income?->value;
        $model->partner = $player->partner?->value;
        $model->dead_flg = $player->isDeleted;

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
                Sense::from($view->a_sense),
            ),
            Evaluation::from(
                Business::from($view->e_business),
                Happiness::from($view->e_happiness),
            ),
            BackgroundId::tryFrom($view->bg_id),
            PlayerFaceId::tryFrom($view->player_face_id),
            AgeGroupCode::from($view->age_grp_cd),
            Job::tryFrom($view->job),
            Income::tryFrom($view->income),
            Partner::tryFrom($view->partner),
            $view->dead_flg,
        );
    }
}
