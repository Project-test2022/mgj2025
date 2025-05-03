<?php

namespace App\ValueObjects;

final readonly class Ability
{
    private function __construct(
        public Intelligence $intelligence,
        public Sport $sport,
        public Visual $visual,
    ) {
    }

    public static function from(
        Intelligence $intelligence,
        Sport $sport,
        Visual $visual,
    ): self {
        return new self(
            $intelligence,
            $sport,
            $visual,
        );
    }
}
