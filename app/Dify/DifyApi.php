<?php

namespace App\Dify;

use App\Entities\Event;
use App\Entities\EventResult;
use App\Entities\Player;
use App\Models\SexModel;
use App\Repositories\PlayerFaceRepository;
use App\ValueObjects\BirthYear;
use App\ValueObjects\Content;
use App\ValueObjects\EventSituation;
use App\ValueObjects\PlayerId;
use App\ValueObjects\Choice;
use App\ValueObjects\PlayerName;
use App\ValueObjects\SelectContent;
use App\ValueObjects\SexName;
use CURLFile;
use Exception;
use TypeError;

final readonly class DifyApi
{
    private string $apiKey;
    private string $endpoint;
    private bool $enabled;
    private bool $imageEnabled;
    private string $imageUrl;

    public function __construct(
        private PlayerFaceRepository $playerFaceRepository,
    ) {
        $this->apiKey = config('app.dify.api_key');
        $this->endpoint = config('app.dify.endpoint');
        $this->enabled = config('app.dify.enabled');
        $this->imageEnabled = config('app.dify.image_enabled');
        $this->imageUrl = config('app.dify.image_url');
    }

    /**
     * @throws Exception
     */
    public function createPlayer(PlayerId $id, ?PlayerName $name, ?BirthYear $birthYear, ?SexName $sexName): array
    {
        if (!$this->enabled) {
            return [
                $name ?? PlayerName::from('山田 太郎'),
                $sexName ?? SexName::from('男'),
                $birthYear ?? BirthYear::from('2025'),
            ];
        }

        $state = State::PLAYER_GENERATION;
        $playerInfo = $this->formatExcludePlayer($name, $birthYear, $sexName);
        $input = $this->input($state, $playerInfo);

        $data = $this->handle($id, $input);
        $data = $data['structured_output'];
        $name = $name ?? PlayerName::from($data['name']);
        $birthYear = $birthYear ?? BirthYear::from($data['birth']);
        $sexName = $sexName ?? SexName::from(SexModel::query()->where('sex_cd', $data['sex'])->first()->sex_nm);

        return [
            $name,
            $sexName,
            $birthYear,
        ];
    }

    /**
     * @throws Exception
     */
    public function createPlayerImage(PlayerId $id): string
    {
        if (!$this->enabled || !$this->imageEnabled) {
            return asset('images/player-default.png');
        }

        $state = State::PLAYER_IMAGE_GENERATION;
        $input = $this->input($state);
        $data = $this->handle($id, $input);

        $relativeUrl = $data['files'][0]['url'];
        return $this->imageUrl . $relativeUrl;
    }

    public function createPlayerNextImage(Player $player): string
    {
        if (!$this->enabled || !$this->imageEnabled) {
            return asset('images/player-default.png');
        }

        // 今の画像をDifyにアップロードする
        $imageId = $this->uploadPlayerImage($player);

        // 画像を生成する
        $relativeUrl = $this->handleNextImage($player, $imageId);

        return $this->imageUrl . $relativeUrl;
    }

    /**
     * @throws Exception
     */
    public function event(Player $player, EventSituation $situation): Event
    {
        if (!$this->enabled) {
            return Event::dummy();
        }

        $state = State::EVENT_OCCURRENCE;
        $playerInfo = $this->formatPlayer($player);
        $input = $this->input($state, $playerInfo, $situation);

        $data = $this->handle($player->id, $input);
        $data = $data['structured_output'];
        return Event::fromArray($data);
    }

    /**
     * @throws Exception
     */
    public function eventResult(Player $player, EventSituation $situation, Event $event, Choice $select, bool $result): EventResult
    {
        if (!$this->enabled) {
            return EventResult::dummy($result);
        }

        $state = State::EVENT_SELECTION;
        $playerInfo = $this->formatPlayer($player);
        $eventInfo = $this->formatEvent($event->content, $select->content, $result);
        $input = $this->input($state, $playerInfo, $situation, $eventInfo);

        $data = $this->handle($player->id, $input);
        $data = $data['structured_output'];
        return EventResult::from($data, $player, $result);
    }

    /**
     * @throws Exception
     */
    private function handle(PlayerId $player_id, array $input): array
    {
        $attempt = 0;
        while ($attempt < 5) {
            try {
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
                    throw new Exception('cURL error: ' . curl_error($ch));
                } else {
                    $decoded = json_decode($response, true);
                }

                // セッション終了
                curl_close($ch);

                // レスポンスの検証
                if (empty($decoded['data']['outputs'])) {
                    throw new Exception('Invalid response from Dify API.');
                }

                return $decoded['data']['outputs'];
            } catch (TypeError|Exception $e) {
                $attempt++;
                if ($attempt > 5) {
                    throw $e;
                }
            }
        }
        throw new Exception('Dify API request failed after 5 attempts.');
    }


    private function formatExcludePlayer(?PlayerName $name, ?BirthYear $birthYear, ?SexName $sexName): string
    {
        $playerInfo = [];
        if ($name !== null) {
            $playerInfo['プレイヤー名'] = $name->value;
        }
        if ($birthYear !== null) {
            $playerInfo['生年'] = $birthYear->value;
        }
        if ($sexName !== null) {
            $playerInfo['性別'] = $sexName->value;
        }
        $formatted = '';
        foreach ($playerInfo as $key => $value) {
            $formatted .= "[$key]$value\n";
        }
        return $formatted;
    }

    private function formatPlayer(Player $player): string
    {
        $playerInfo = [
            'プレイヤー名' => $player->name->value,
            '性別' => $player->sexName->value,
            '生年' => $player->birthYear->value,
            '年齢' => $player->turn->value,
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

    private function uploadPlayerImage(Player $player): string
    {
        // 今の画像を取得してローカルに保存する
        $player_face = $this->playerFaceRepository->find($player->playerFaceId);
        $fileName = 'player_' . $player->id->value . '.png';
        $path = storage_path('app/private/player/' . $fileName);
        file_put_contents($path, $player_face->image);

        // Dify APIにリクエストを送信
        $uploadUrl = $this->imageUrl . '/v1/files/upload';
        $mimeType = mime_content_type($path);
        $ch = curl_init();
        $postFields = [
            'file' => new CURLFile($path, $mimeType, $fileName),
            'user' => $player->id->value,
        ];
        curl_setopt_array($ch, [
            CURLOPT_URL => $uploadUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiKey,
            ],
        ]);
        $uploadResponse = curl_exec($ch);
        curl_close($ch);
        $uploadResult = json_decode($uploadResponse, true);
        $imageId = $uploadResult['id'];
        // ローカルの画像を削除する
        unlink($path);

        return $imageId;
    }

    private function handleNextImage(Player $player, string $imageId): string
    {
        $endpoint = $this->imageUrl . '/v1/workflows/run';
        $state = State::PLAYER_NEXT_IMAGE_GENERATION;
        $playerInfo = $this->formatPlayer($player);
        $input = $this->input($state, $playerInfo);
        $input['sys_image'] = [
            'transfer_method' => 'local_file',
            'upload_file_id' => $imageId,
            'type' => 'image',
        ];
        $data = [
            'inputs' => $input,
            'response_mode' => 'blocking',
            'user' => $player->id->value,
        ];
        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey,
        ]);

        $response = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($response, true);

        return $result['data']['outputs']['files'][0]['url'];
    }
}
