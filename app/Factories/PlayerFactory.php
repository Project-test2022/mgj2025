<?php

namespace App\Factories;

use App\Entities\Player;
use App\ValueObjects\Ability;
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

final readonly class PlayerFactory
{
    public function create(): Player
    {
        return new Player(
            PlayerId::from(uniqid()), // TODO: 一意なIDを生成する
            PlayerName::from(''), // TODO: ランダムに生成する
            SexName::from('男'),// TODO: ランダムに生成する
            BirthYear::from(2025),
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
            )
        );
    }
}
