<?php

namespace App\Entities;

use App\ValueObjects\Ability;
use App\ValueObjects\Age;
use App\ValueObjects\BirthYear;
use App\ValueObjects\Evaluation;
use App\ValueObjects\Health;
use App\ValueObjects\Money;
use App\ValueObjects\PlayerName;
use App\ValueObjects\PlayerId;
use App\ValueObjects\Sex;
use App\ValueObjects\Turn;

final readonly class Player
{
    public function __construct(
        public PlayerId $id,
        public Age $age,
        public BirthYear $birthYear,
        public Sex $sex,
        public Money $money,
        public PlayerName $name,
        public Health $health,
        public Ability $ability,
        public Evaluation $evaluation,
    ) {
    }

    public function turn(): Turn
    {
        // 年齢と誕生年からターンを計算する
        // 例えば、年齢が20歳で誕生年が2000年の場合、ターンは20になる
        return Turn::from($this->age->value - $this->birthYear->value);
    }
}
