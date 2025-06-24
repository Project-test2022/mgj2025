<?php

namespace App\Dify;

use App\Entities\Event;
use App\Entities\EventResult;
use App\Entities\Player;
use App\Parameters\CreatePlayerParameters;
use App\Repositories\PlayerFaceRepository;
use App\ValueObjects\Ability;
use App\ValueObjects\Action;
use App\ValueObjects\BirthYear;
use App\ValueObjects\Content;
use App\ValueObjects\Health;
use App\ValueObjects\Income;
use App\ValueObjects\Intelligence;
use App\ValueObjects\Job;
use App\ValueObjects\Money;
use App\ValueObjects\PlayerId;
use App\ValueObjects\Choice;
use App\ValueObjects\PlayerName;
use App\ValueObjects\SelectContent;
use App\ValueObjects\Sense;
use App\ValueObjects\SexCode;
use App\ValueObjects\Sport;
use App\ValueObjects\Visual;
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
    public function createPlayer(PlayerId $id, ?PlayerName $name, ?BirthYear $birthYear, ?SexCode $sexCode): CreatePlayerParameters
    {
        if ($name !== null && $birthYear !== null && $sexCode !== null) {
            return new CreatePlayerParameters(
                $name,
                $birthYear,
                $sexCode,
                Money::from(1000000),
                Health::from(100),
                Ability::from(
                    Intelligence::from(0),
                    Sport::from(0),
                    Visual::from(0),
                    Sense::from(0),
                ),
                Job::from('赤ちゃん'),
            );
        }

        if (!$this->enabled) {
            return new CreatePlayerParameters(
                $name ?? PlayerName::from('山田 太郎'),
                $birthYear ?? BirthYear::from('2025'),
                $sexCode ?? SexCode::from(0),
                Money::from(1000000),
                Health::from(100),
                Ability::from(
                    Intelligence::from(0),
                    Sport::from(0),
                    Visual::from(0),
                    Sense::from(0),
                ),
                Job::from('赤ちゃん'),
            );
        }

        $state = State::PLAYER_GENERATION;
        $input = $this->input($state);

        $data = $this->handle($id, $input);
        $data = $data['structured_output'];
        $name = $name ?? PlayerName::from($data['name']);
        $birthYear = $birthYear ?? BirthYear::from($data['birth']);
        $sexCode = $sexCode ?? SexCode::from($data['sex']);
        $totalMoney = Money::from($data['total_money']);
        $health = Health::from($data['health']);
        $intelligence = Intelligence::from($data['a_intelligence']);
        $sport = Sport::from($data['a_sport']);
        $visual = Visual::from($data['a_visual']);
        $sense = Sense::from($data['a_sense']);
        $job = Job::from($data['job']);

        return new CreatePlayerParameters(
            $name,
            $birthYear,
            $sexCode,
            $totalMoney,
            $health,
            Ability::from(
                $intelligence,
                $sport,
                $visual,
                $sense,
            ),
            $job,
        );
    }

    /**
     * @throws Exception
     */
    public function createPlayerImage(Player $player): string
    {
        if (!$this->enabled || !$this->imageEnabled) {
            return resource_path('images/player-default.png');
        }

        $state = State::PLAYER_IMAGE_GENERATION;
        $playerInfo = $this->formatPlayer($player);
        $input = $this->input($state, $playerInfo);
        $data = $this->handle($player->id, $input);

        $relativeUrl = $data['files'][0]['url'];
        return $this->imageUrl . $relativeUrl;
    }

    public function createPlayerNextImage(Player $player): string
    {
        if (!$this->enabled || !$this->imageEnabled) {
            return resource_path('images/player-default.png');
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
    public function actions(Player $player): array
    {
        if (!$this->enabled) {
            return [
                Action::from('仕事'),
                Action::from('遊び'),
                Action::from('勉強'),
                Action::from('運動'),
                Action::from('恋愛'),
            ];
        }

        $state = State::ACTION;
        $playerInfo = $this->formatPlayer($player);
        $input = $this->input($state, $playerInfo);

        $data = $this->handle($player->id, $input);
        $data = $data['structured_output'];
        return [
            Action::from($data['command_1'] ?? '仕事'),
            Action::from($data['command_2'] ?? '遊び'),
            Action::from($data['command_3'] ?? '勉強'),
            Action::from($data['command_4'] ?? '運動'),
            Action::from($data['command_5'] ?? '恋愛'),
        ];
    }

    /**
     * @throws Exception
     */
    public function event(Player $player, Action $action): Event
    {
        if (!$this->enabled) {
            return Event::dummy();
        }

        $state = State::EVENT_OCCURRENCE;
        $playerInfo = $this->formatPlayer($player);
        $input = $this->input($state, $playerInfo, $action);

        $data = $this->handle($player->id, $input);
        $data = $data['structured_output'];
        return Event::fromArray($data);
    }

    /**
     * @throws Exception
     */
    public function eventResult(Player $player, Action $action, Event $event, Choice $select, bool $result): EventResult
    {
        if (!$this->enabled) {
            return EventResult::dummy($result);
        }

        $state = State::EVENT_SELECTION;
        $playerInfo = $this->formatPlayer($player);
        $eventInfo = $this->formatEvent($event->content, $select->content, $result);
        $input = $this->input($state, $playerInfo, $action, $eventInfo);

        $data = $this->handle($player->id, $input);
        if (!isset($data['output'])) {
            $data = $data['structured_output'];
            $job = $player->job;
        } else {
            $job = Job::from($data['new_job']);
            $data = $data['output'];
        }
        return EventResult::from($data, $job, $player, $result);
    }

    /**
     * @throws Exception
     */
    public function income(Player $player, Job $job): Income
    {
        if (!$this->enabled) {
            return Income::from('555555');
        }

        $state = State::INCOME;
        $playerInfo = $this->formatPlayer($player, $job);
        $input = $this->input($state, $playerInfo);

        $data = $this->handle($player->id, $input);

        return Income::from($data['income']);
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

    private function formatPlayer(Player $player, ?Job $job = null): array
    {
        $playerInfo = [
            'プレイヤー名' => $player->name->value,
            '性別' => $player->sexName->value,
            '生年' => $player->birthYear->value,
            '総資産(円)' => $player->totalMoney->value,
            '健康度(0-100)' => $player->health->value,
            '知能(0-100)' => $player->ability->intelligence->value,
            '運動(0-100)' => $player->ability->sport->value,
            '容姿(0-100)' => $player->ability->visual->value,
            '仕事(0-100)' => $player->evaluation->business->value,
            '幸福(0-100)' => $player->evaluation->happiness->value,
        ];
        if (!$job) {
            $playerInfo['年収(円)'] = $player->income->value;
            $playerInfo['職業'] = $player->job->value;
        } else {
            $playerInfo['職業'] = $job->value;
        }

        $formatted = '';
        foreach ($playerInfo as $key => $value) {
            $formatted .= "[$key]$value\n";
        }
        $result['sys_player'] = $formatted;
        $result['sys_age'] = $player->turn->value;
        $result['sys_health'] = $player->health->value;

        return $result;
    }

    private function input(State $state, array $playerInfo = [], ?Action $action = null, ?string $event = null): array
    {
        $result = [
            'sys_state' => $state->value,
        ];
        if ($playerInfo !== []) {
            $result = array_merge($result, $playerInfo);
        }
        if ($action !== null) {
            $result['sys_situation'] = $action->value;
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
