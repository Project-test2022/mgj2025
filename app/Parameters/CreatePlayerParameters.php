<?php

namespace App\Parameters;

use App\ValueObjects\Ability;
use App\ValueObjects\BirthYear;
use App\ValueObjects\Health;
use App\ValueObjects\Job;
use App\ValueObjects\Money;
use App\ValueObjects\PlayerName;
use App\ValueObjects\SexCode;

final readonly class CreatePlayerParameters
{
    public function __construct(
        public PlayerName $name,
        public BirthYear $birthYear,
        public SexCode $sexCode,
        public Money $totalMoney,
        public Health $health,
        public Ability $ability,
        public Job $job,
    ) {
    }
}
