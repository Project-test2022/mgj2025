<?php

namespace App\Services;

use App\Dify\DifyApi;
use App\Entities\Event;
use App\Entities\EventResult;
use App\Entities\Player;
use App\ValueObjects\Action;
use App\ValueObjects\Choice;
use Exception;

final readonly class EventAppService
{
    public function __construct(
        private DifyApi $dify,
    ) {
    }

    /**
     * @throws Exception
     */
    public function generate(Player $player, Action $action): Event
    {
        return $this->dify->event($player, $action);
    }

    /**
     * @throws Exception
     */
    public function result(Player $player, Action $action, Event $event, Choice $choice): EventResult
    {
        // イベントの成否を抽選
        $result = $choice->result();

        // イベントの結果を取得
        return $this->dify->eventResult(
            $player,
            $action,
            $event,
            $choice,
            $result,
        );
    }

    /**
     * @param Player $player
     * @return array<Action>
     * @throws Exception
     */
    public function generateActions(Player $player): array
    {
        return $this->dify->actions($player);
    }
}
