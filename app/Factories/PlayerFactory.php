<?php

namespace App\Factories;

use App\Entities\Player;
use App\Models\PlayerModel;
use App\Models\SexModel;
use App\Parameters\CreatePlayerParameters;
use App\ValueObjects\Business;
use App\ValueObjects\Evaluation;
use App\ValueObjects\Happiness;
use App\ValueObjects\Income;
use App\ValueObjects\Job;
use App\ValueObjects\PlayerId;
use App\ValueObjects\SexName;
use App\ValueObjects\Turn;

final readonly class PlayerFactory
{
    public function generateId(): PlayerId {
        do {
            $playerId = uniqid();
        } while (PlayerModel::query()->where('player_id', $playerId)->exists());

        return PlayerId::from($playerId);
    }

    public function create(PlayerId $id, CreatePlayerParameters $parameters): Player {
        $sexName = SexModel::query()->where('sex_cd', $parameters->sexCode->value)->first()->sex_nm;

        return new Player(
            $id,
            $parameters->name,
            SexName::from($sexName),
            $parameters->birthYear,
            Turn::from(0),
            $parameters->totalMoney,
            $parameters->health,
            $parameters->ability,
            Evaluation::from(
                Business::from(0),
                Happiness::from(0),
            ),
            null,
            null,
            null,
            Job::from('赤ちゃん'),
            Income::from(0),
            null,
        );
    }
}
