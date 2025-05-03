<?php

namespace App\Entities;

use App\ValueObjects\Ability;
use App\ValueObjects\BirthYear;
use App\ValueObjects\Evaluation;
use App\ValueObjects\Health;
use App\ValueObjects\Money;
use App\ValueObjects\PlayerName;
use App\ValueObjects\PlayerId;
use App\ValueObjects\SexName;
use App\ValueObjects\Turn;

final readonly class Player
{
    public function __construct(
        public PlayerId $id,
        public PlayerName $name,
        public SexName $sexName,
        public BirthYear $birthYear,
        public Turn $turn,
        public Money $total_money,
        public Health $health,
        public Ability $ability,
        public Evaluation $evaluation,
    ) {
    }

    public function age(): int
    {
        // 誕生年からの年数 + ターン数
        $thisYear = date('Y');
        return (int)$thisYear - (int)$this->birthYear->value + (int)$this->turn->value;
    }
}
