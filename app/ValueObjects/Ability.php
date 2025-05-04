<?php

namespace App\ValueObjects;

final readonly class Ability
{
    private function __construct(
        public Intelligence $intelligence,
        public Sport $sport,
        public Visual $visual,
        public Sense $sense,
    ) {
    }

    public static function from(
        Intelligence $intelligence,
        Sport $sport,
        Visual $visual,
        Sense $sense,
    ): self {
        return new self(
            $intelligence,
            $sport,
            $visual,
            $sense,
        );
    }

    public function add(Ability $other): self
    {
        return new self(
            $this->intelligence->add($other->intelligence),
            $this->sport->add($other->sport),
            $this->visual->add($other->visual),
            $this->sense->add($other->sense),
        );
    }
}
