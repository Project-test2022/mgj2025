<?php

namespace App\Entities;

use App\ValueObjects\Content;
use App\ValueObjects\Choice;
use Illuminate\Http\Request;

final readonly class Event
{
    public function __construct(
        public Content $content,
        public Choice $choice1,
        public Choice $choice2,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            Content::from($request->input('event')),
            Choice::from(
                $request->input('choice1'),
                $request->input('rate1'),
            ),
            Choice::from(
                $request->input('choice2'),
                $request->input('rate2'),
            ),
        );
    }

    public static function fromArray(array $data): self
    {
        $output = $data['data']['outputs']['structured_output'];
        return new self(
            Content::from($output['e_body']),
            Choice::from(
                $output['e_choice1'],
                $output['e_rate1'],
            ),
            Choice::from(
                $output['e_choice2'],
                $output['e_rate2'],
            ),
        );
    }
}
