<?php

namespace App\Entities;

use App\ValueObjects\Ability;
use App\ValueObjects\Business;
use App\ValueObjects\Evaluation;
use App\ValueObjects\Health;
use App\ValueObjects\Intelligence;
use App\ValueObjects\Love;
use App\ValueObjects\Money;
use App\ValueObjects\ResultMessage;
use App\ValueObjects\Sport;
use App\ValueObjects\Visual;

final readonly class EventResult
{
    /**
     * @param ResultMessage $message
     * @param bool          $success
     * @param Money         $totalMoney 総資産の変化量
     * @param Health        $health     健康の変化量
     * @param Ability       $ability    能力の変化量
     * @param Evaluation    $evaluation 評価の変化量
     */
    public function __construct(
        public ResultMessage $message,
        public bool $success,
        public Money $totalMoney,
        public Health $health,
        public Ability $ability,
        public Evaluation $evaluation,
    ) {
    }

    public static function from(array $data, Player $before, bool $success): self
    {
        return new self(
            ResultMessage::from($data['e_result']),
            $success,
            Money::from($data['total_money'])->sub($before->totalMoney),
            Health::from($data['health'])->sub($before->health),
            Ability::from(
                Intelligence::from($data['a_intelligence'])->sub($before->ability->intelligence),
                Sport::from($data['a_sport'])->sub($before->ability->sport),
                Visual::from($data['a_visual'])->sub($before->ability->visual),
            ),
            Evaluation::from(
                Business::from($data['e_business'])->sub($before->evaluation->business),
                Love::from($data['e_love'])->sub($before->evaluation->love),
            ),
        );
    }

    public function result(): string
    {
        if ($this->success) {
            return 'SUCCESS';
        } else {
            return 'FAILURE';
        }
    }
}
