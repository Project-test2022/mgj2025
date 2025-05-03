<?php

namespace App\Factories;

use App\Entities\Player;
use App\ValueObjects\Ability;
use App\ValueObjects\Age;
use App\ValueObjects\BirthYear;
use App\ValueObjects\Business;
use App\ValueObjects\Evaluation;
use App\ValueObjects\Health;
use App\ValueObjects\Intelligence;
use App\ValueObjects\Love;
use App\ValueObjects\Money;
use App\ValueObjects\PlayerId;
use App\ValueObjects\PlayerName;
use App\ValueObjects\Sex;
use App\ValueObjects\Sport;
use App\ValueObjects\Visual;

final readonly class PlayerFactory
{
    public function create(): Player
    {
        return new Player(
            PlayerId::from(uniqid()), // TODO: 一意なIDを生成する
            Age::from(0),
            BirthYear::from(2025),
            Sex::MALE,// TODO: ランダムに生成する
            Money::from(0),
            PlayerName::from(''), // TODO: ランダムに生成する
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
