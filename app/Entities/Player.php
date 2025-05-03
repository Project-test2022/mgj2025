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
        public Money $totalMoney,
        public Health $health,
        public Ability $ability,
        public Evaluation $evaluation,
    ) {
    }

    public function update(Money $totalMoney, Health $health, Ability $ability, Evaluation $evaluation): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->sexName,
            $this->birthYear,
            $this->turn,
            $this->totalMoney->add($totalMoney),
            $this->health->add($health),
            $this->ability->add($ability),
            $this->evaluation->add($evaluation),
        );
    }

    public function nextTurn(): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->sexName,
            $this->birthYear,
            $this->turn->next(),
            $this->totalMoney,
            $this->health,
            $this->ability,
            $this->evaluation,
        );
    }
}
