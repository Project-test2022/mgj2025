<?php

namespace App\Entities;

use App\ValueObjects\Ability;
use App\ValueObjects\Business;
use App\ValueObjects\Evaluation;
use App\ValueObjects\Health;
use App\ValueObjects\Intelligence;
use App\ValueObjects\Happiness;
use App\ValueObjects\Money;
use App\ValueObjects\ResultMessage;
use App\ValueObjects\Sense;
use App\ValueObjects\Sport;
use App\ValueObjects\Visual;

final readonly class EventResult
{
    /**
     * @param ResultMessage $message
     * @param bool          $success
     * @param bool          $dead
     * @param Money         $totalMoney 総資産の変化量
     * @param Health        $health     健康の変化量
     * @param Ability       $ability    能力の変化量
     * @param Evaluation    $evaluation 評価の変化量
     */
    public function __construct(
        public ResultMessage $message,
        public bool $success,
        public bool $dead,
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
            $data['e_dead'],
            Money::from($data['total_money'])->sub($before->totalMoney),
            Health::from($data['health'])->sub($before->health),
            Ability::from(
                Intelligence::from($data['a_intelligence'])->sub($before->ability->intelligence),
                Sport::from($data['a_sport'])->sub($before->ability->sport),
                Visual::from($data['a_visual'])->sub($before->ability->visual),
                Sense::from($data['a_sense'])->sub($before->ability->sense),
            ),
            Evaluation::from(
                Business::from($data['e_business'])->sub($before->evaluation->business),
                Happiness::from($data['e_happiness'])->sub($before->evaluation->happiness),
            ),
        );
    }

    public static function dummy(bool $result): self
    {
        $value = $result ? 10 : -10;
        return new self(
            ResultMessage::from('ダミー結果'),
            $result,
            false,
            Money::from($value),
            Health::from($value),
            Ability::from(
                Intelligence::from($value),
                Sport::from($value),
                Visual::from($value),
                Sense::from($value),
            ),
            Evaluation::from(
                Business::from($value),
                Happiness::from($value),
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

    public function money(): string
    {
        return $this->totalMoney->format();
    }

    public function health(): string
    {
        if ($this->health->value > 0) {
            return '+' . $this->health->value;
        } elseif ($this->health->value < 0) {
            return (string)$this->health->value;
        } else {
            return '0';
        }
    }

    public function intelligence(): string
    {
        if ($this->ability->intelligence->value > 0) {
            return '+' . $this->ability->intelligence->value;
        } elseif ($this->ability->intelligence->value < 0) {
            return (string)$this->ability->intelligence->value;
        } else {
            return '0';
        }
    }

    public function sport(): string
    {
        if ($this->ability->sport->value > 0) {
            return '+' . $this->ability->sport->value;
        } elseif ($this->ability->sport->value < 0) {
            return (string)$this->ability->sport->value;
        } else {
            return '0';
        }
    }

    public function visual(): string
    {
        if ($this->ability->visual->value > 0) {
            return '+' . $this->ability->visual->value;
        } elseif ($this->ability->visual->value < 0) {
            return (string)$this->ability->visual->value;
        } else {
            return '0';
        }
    }

    public function sense(): string
    {
        if ($this->ability->sense->value > 0) {
            return '+' . $this->ability->sense->value;
        } elseif ($this->ability->sense->value < 0) {
            return (string)$this->ability->sense->value;
        } else {
            return '0';
        }
    }

    public function business(): string
    {
        if ($this->evaluation->business->value > 0) {
            return '+' . $this->evaluation->business->value;
        } elseif ($this->evaluation->business->value < 0) {
            return $this->evaluation->business->value;
        } else {
            return '0';
        }
    }

    public function happiness(): string
    {
        if ($this->evaluation->happiness->value > 0) {
            return '+' . $this->evaluation->happiness->value;
        } elseif ($this->evaluation->happiness->value < 0) {
            return (string)$this->evaluation->happiness->value;
        } else {
            return '0';
        }
    }
}
