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
}
