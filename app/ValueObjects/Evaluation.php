<?php

namespace App\ValueObjects;

final readonly class Evaluation
{
    private function __construct(
        public Business $business,
        public Love $love,
    ) {
    }

    public static function from(
        Business $business,
        Love $love,
    ): self {
        return new self(
            $business,
            $love,
        );
    }

    public function add(Evaluation $other): self
    {
        return new self(
            $this->business->add($other->business),
            $this->love->add($other->love),
        );
    }
}
