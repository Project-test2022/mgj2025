<?php

namespace App\Factories;

use App\Entities\Player;
use App\Models\PlayerModel;
use App\ValueObjects\Ability;
use App\ValueObjects\BackgroundId;
use App\ValueObjects\BirthYear;
use App\ValueObjects\Business;
use App\ValueObjects\Evaluation;
use App\ValueObjects\Health;
use App\ValueObjects\Intelligence;
use App\ValueObjects\Love;
use App\ValueObjects\Money;
use App\ValueObjects\PlayerId;
use App\ValueObjects\PlayerName;
use App\ValueObjects\SexName;
use App\ValueObjects\Sport;
use App\ValueObjects\Turn;
use App\ValueObjects\Visual;
use Illuminate\Support\Facades\DB;

final readonly class PlayerFactory
{
    public function generateId(): PlayerId {
        do {
            $playerId = uniqid();
        } while (PlayerModel::query()->where('player_id', $playerId)->exists());

        return PlayerId::from($playerId);
    }

    public function create(PlayerId $id, PlayerName $name, SexName $sex, BirthYear $birthYear): Player {
        $bg_id = DB::table('background')
            ->whereNull('border_money_s')
            ->select('bg_id')
            ->first()
            ->bg_id;

        return new Player(
            $id,
            $name,
            $sex,
            $birthYear,
            Turn::from(0),
            Money::from(0),
            Health::from(100),
            Ability::from(
                Intelligence::from(0),
                Sport::from(0),
                Visual::from(0),
            ),
            Evaluation::from(
                Business::from(0),
                Love::from(0),
            ),
            BackgroundId::from($bg_id),
            null,
        );
    }
}
