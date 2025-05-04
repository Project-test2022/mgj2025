<?php

namespace App\Entities;

use App\ValueObjects\Ability;
use App\ValueObjects\Business;
use App\ValueObjects\Evaluation;
use App\ValueObjects\Health;
use App\ValueObjects\Income;
use App\ValueObjects\Intelligence;
use App\ValueObjects\Happiness;
use App\ValueObjects\Job;
use App\ValueObjects\Money;
use App\ValueObjects\Partner;
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
     * @param Job|null      $job        変更後の職業
     * @param Income        $income     年収の変化量
     * @param Partner|null  $partner    変更後のパートナー
     */
    public function __construct(
        public ResultMessage $message,
        public bool $success,
        public bool $dead,
        public Money $totalMoney,
        public Health $health,
        public Ability $ability,
        public Evaluation $evaluation,
        public ?Job $job,
        public Income $income,
        public ?Partner $partner,
    ) {
    }

    public static function from(array $data, Job $job, Player $before, bool $success): self
    {
        return new self(
            ResultMessage::from($data['e_result']),
            $success,
            $data['e_dead'] ?? false,
            Money::from($data['total_money'] ?? 0)->sub($before->totalMoney),
            Health::from($data['health'] ?? 0)->sub($before->health),
            Ability::from(
                Intelligence::from($data['a_intelligence'] ?? 0)->sub($before->ability->intelligence),
                Sport::from($data['a_sport'] ?? 0)->sub($before->ability->sport),
                Visual::from($data['a_visual'] ?? 0)->sub($before->ability->visual),
                Sense::from($data['a_sense'] ?? 0)->sub($before->ability->sense),
            ),
            Evaluation::from(
                Business::from($data['e_business'] ?? 0)->sub($before->evaluation->business),
                Happiness::from($data['e_happiness'] ?? 0)->sub($before->evaluation->happiness),
            ),
            $job,
            Income::from($data['income'] ?? 0)->sub($before->income),
            Partner::tryFrom($data['partner']),
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
            null,
            Income::from($value),
            null,
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
        $value = number_format((abs($this->totalMoney->value)));
        if ($this->totalMoney->value < 0) {
            return '-¥' . $value;
        } else {
            return '+¥' . $value;
        }
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

    public function income(): string
    {
        $value = number_format((abs($this->income->value)));
        if ($this->income->value < 0) {
            return '-¥' . $value;
        } else {
            return '+¥' . $value;
        }
    }
}
