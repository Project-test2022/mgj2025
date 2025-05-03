<?php

namespace App\Dify;

use App\Entities\Event;
use App\Entities\Player;
use App\ValueObjects\Content;
use App\ValueObjects\EventSituation;
use App\ValueObjects\PlayerId;
use App\ValueObjects\Choice;
use App\ValueObjects\SelectContent;

final readonly class DifyApi
{
    private string $apiKey;
    private string $endpoint;

    public function __construct()
    {
        $this->apiKey = config('app.dify.api_key');
        $this->endpoint = config('app.dify.endpoint');
    }

    public function event(Player $player, EventSituation $situation): Event
    {
        $state = State::EVENT_OCCURRENCE;
        $playerInfo = $this->formatPlayer($player);
        $input = $this->input($state, $playerInfo, $situation);

        $data = $this->handle($player->id, $input);
        return Event::fromArray($data);
    }

    public function eventResult(Player $player, EventSituation $situation, Event $event, Choice $select, bool $result): array
    {
        $state = State::EVENT_SELECTION;
        $playerInfo = $this->formatPlayer($player);
        $eventInfo = $this->
        formatEvent($event->content, $select->content, $result);
        $input = $this->input($state, $playerInfo, $situation, $eventInfo);

        return $this->handle($player->id, $input);
    }

    private function handle(PlayerId $player_id, array $input): array
    {
        $data = [
            'inputs' => $input,
            'user' => $player_id->value,
        ];

        // JSONにエンコード
        $jsonData = json_encode($data);

        // cURLセッション初期化
        $ch = curl_init($this->endpoint);

        // オプション設定
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey,
        ]);

        // 実行とレスポンス取得
        $response = curl_exec($ch);

        // エラーハンドリング
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
            return [];
        } else {
            $decoded = json_decode($response, true);
            print_r($decoded);
        }

        // セッション終了
        curl_close($ch);

        return $decoded;
    }

    private function formatPlayer(Player $player): string
    {
        $playerInfo = [
            'プレイヤー名' => $player->name->value,
            '性別' => $player->sexName->value,
            '生年' => $player->birthYear->value,
            '年齢' => $player->age(),
            '総資産(円)' => $player->totalMoney->value,
            '健康度(0-100)' => $player->health->value,
            '知能(0-100)' => $player->ability->intelligence->value,
            '運動(0-100)' => $player->ability->sport->value,
            '容姿(0-100)' => $player->ability->visual->value,
            '仕事(0-100)' => $player->evaluation->business->value,
            '恋愛(0-100)' => $player->evaluation->love->value,
        ];

        $formatted = '';
        foreach ($playerInfo as $key => $value) {
            $formatted .= "[$key]$value\n";
        }
        return $formatted;
    }

    private function input(State $state, ?string $playerInfo = null, ?EventSituation $situation = null, ?string $event = null): array
    {
        $result = [
            'sys_state' => $state->value,
        ];
        if ($playerInfo !== null) {
            $result['sys_player'] = $playerInfo;
        }
        if ($situation !== null) {
            $result['sys_situation'] = $situation->label();
        }
        if ($event !== null) {
            $result['sys_event'] = $event;
        }

        return $result;
    }

    private function formatEvent(Content $event, SelectContent $select, bool $result): string
    {
        $result_text = $result ? '成功' : '失敗';
        return "$event\n[プレイヤーの選択]{$select}（{$result_text}）";
    }
}
