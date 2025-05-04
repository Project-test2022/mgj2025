<?php

namespace App\Services;

use App\Dify\DifyApi;
use App\Entities\Event;
use App\Entities\EventResult;
use App\Entities\Player;
use App\ValueObjects\Choice;
use App\ValueObjects\EventSituation;

final readonly class EventAppService
{
    public function __construct(
        private DifyApi $dify,
    ) {
    }

    public function generate(Player $player, EventSituation $situation): Event
    {
        return $this->dify->event($player, $situation);
    }

    public function result(Player $player, EventSituation $situation, Event $event, Choice $choice): EventResult
    {
        // イベントの成否を抽選
        $result = $choice->result();

        // イベントの結果を取得
        return $this->dify->eventResult(
            $player,
            $situation,
            $event,
            $choice,
            $result,
        );
    }
}
