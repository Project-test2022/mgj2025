<?php

namespace App\ValueObjects;

final readonly class Evaluation
{
    private function __construct(
        public Business $business,
        public Happiness $happiness,
    ) {
    }

    public static function from(
        Business $business,
        Happiness $happiness,
    ): self {
        return new self(
            $business,
            $happiness,
        );
    }

    public function add(Evaluation $other): self
    {
        return new self(
            $this->business->add($other->business),
            $this->happiness->add($other->happiness),
        );
    }
}
