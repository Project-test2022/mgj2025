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
            $before->totalMoney->sub(Money::from($data['total_money'])),
            $before->health->sub(Health::from($data['health'])),
            Ability::from(
                $before->ability->intelligence->sub(Intelligence::from($data['a_intelligence'])),
                $before->ability->sport->sub(Sport::from($data['a_sport'])),
                $before->ability->visual->sub(Visual::from($data['a_visual'])),
            ),
            Evaluation::from(
                $before->evaluation->business->sub(Business::from($data['e_business'])),
                $before->evaluation->love->sub(Love::from($data['e_love'])),
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
