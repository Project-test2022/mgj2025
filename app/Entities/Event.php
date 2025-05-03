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
        return new self(
            Content::from($data['e_body']),
            Choice::from(
                $data['e_choice1'],
                $data['e_rate1'],
            ),
            Choice::from(
                $data['e_choice2'],
                $data['e_rate2'],
            ),
        );
    }

    public static function dummy(): self
    {
        return new self(
            Content::from('ダミーイベント'),
            Choice::from('成功', 100),
            Choice::from('失敗', 0),
        );
    }
}
