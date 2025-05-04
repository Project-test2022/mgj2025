<?php

namespace App\ValueObjects;

enum EventSituation: int
{
    case BUSINESS = 1;
    case HAPPINESS = 2;

    public function label(): string
    {
        return match ($this) {
            self::BUSINESS => '仕事',
            self::HAPPINESS => '幸福',
        };
    }
}
