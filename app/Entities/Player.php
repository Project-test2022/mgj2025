<?php

namespace App\Entities;

use App\ValueObjects\Ability;
use App\ValueObjects\AgeGroupCode;
use App\ValueObjects\BackgroundId;
use App\ValueObjects\BirthYear;
use App\ValueObjects\Evaluation;
use App\ValueObjects\Health;
use App\ValueObjects\Income;
use App\ValueObjects\Job;
use App\ValueObjects\Money;
use App\ValueObjects\Partner;
use App\ValueObjects\PlayerFaceId;
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
        public ?BackgroundId $backgroundId,
        public ?PlayerFaceId $playerFaceId,
        public ?AgeGroupCode $ageGroupCode,
        public ?Job $job,
        public Income $income,
        public ?Partner $partner,
        public bool $isDead = false,
    ) {
    }

    public function update(
        Money $totalMoney,
        Health $health,
        Ability $ability,
        Evaluation $evaluation,
        ?Job $job,
        Income $income,
        ?Partner $partner,
    ): self {
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
            $this->backgroundId,
            $this->playerFaceId,
            $this->ageGroupCode,
            $job,
            $this->income->add($income),
            $partner,
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
            $this->totalMoney->addIncome($this->income),
            $this->health,
            $this->ability,
            $this->evaluation,
            $this->backgroundId,
            $this->playerFaceId,
            $this->ageGroupCode,
            $this->job,
            $this->income,
            $this->partner,
        );
    }

    public function setFace(PlayerFaceId $playerFaceId, AgeGroupCode $ageGroupCode): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->sexName,
            $this->birthYear,
            $this->turn,
            $this->totalMoney,
            $this->health,
            $this->ability,
            $this->evaluation,
            $this->backgroundId,
            $playerFaceId,
            $ageGroupCode,
            $this->job,
            $this->income,
            $this->partner,
        );
    }

    public function setBackgroundId(BackgroundId $backgroundId): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->sexName,
            $this->birthYear,
            $this->turn,
            $this->totalMoney,
            $this->health,
            $this->ability,
            $this->evaluation,
            $backgroundId,
            $this->playerFaceId,
            $this->ageGroupCode,
            $this->job,
            $this->income,
            $this->partner,
        );
    }

    public function dead(): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->sexName,
            $this->birthYear,
            $this->turn,
            $this->totalMoney,
            $this->health,
            $this->ability,
            $this->evaluation,
            $this->backgroundId,
            $this->playerFaceId,
            $this->ageGroupCode,
            $this->job,
            $this->income,
            $this->partner,
            true,
        );
    }
}
