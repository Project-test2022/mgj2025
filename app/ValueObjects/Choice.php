<?php

namespace App\ValueObjects;

final readonly class Choice
{
    private function __construct(
        public SelectContent $content,
        public SelectRate $rate,
    ) {
    }

    public static function from(string $content, string $rate): self {
        return new self(
            SelectContent::from($content),
            SelectRate::from($rate),
        );
    }
}
