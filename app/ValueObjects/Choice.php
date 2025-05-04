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

    public function result(): bool
    {
        $rate = (float)$this->rate->value;
        return mt_rand(0, 10000) < ($rate * 100);
    }
}
