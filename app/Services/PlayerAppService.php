<?php

namespace App\Services;

use App\Dify\DifyApi;
use App\Entities\EventResult;
use App\Entities\Player;
use App\Factories\PlayerFaceFactory;
use App\Factories\PlayerFactory;
use App\Repositories\AgeGroupRepository;
use App\Repositories\BackgroundRepository;
use App\Repositories\PlayerFaceRepository;
use App\Repositories\PlayerRepository;
use App\ValueObjects\BirthYear;
use App\ValueObjects\PlayerId;
use App\ValueObjects\PlayerName;
use App\ValueObjects\SexCode;
use Exception;

final readonly class PlayerAppService
{
    public function __construct(
        private PlayerFactory $playerFactory,
        private PlayerRepository $playerRepository,
        private DifyApi $dify,
        private PlayerFaceRepository $playerFaceRepository,
        private PlayerFaceFactory $playerFaceFactory,
        private BackgroundRepository $backgroundRepository,
        private AgeGroupRepository $ageGroupRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    public function create(
        ?PlayerName $name,
        ?BirthYear $birthYear,
        ?SexCode $sexCode,
    ): PlayerId {
        // Difyからプレイヤーの情報を生成
        $id = $this->playerFactory->generateId();
        $parameters = $this->dify->createPlayer($id, $name, $birthYear, $sexCode);

        // プレイヤー作成
        $player = $this->playerFactory->create($id, $parameters);
        $this->playerRepository->save($player);

        // プレイヤーの画像を生成
        $imgUrl = $this->dify->createPlayerImage($player);
        $img = Utility::getPngCompressedImage($imgUrl);
        $playerFace = $this->playerFaceFactory->create($id, $img);
        $this->playerFaceRepository->save($playerFace);

        // プレイヤーに画像を設定
        $ageGroup = $this->ageGroupRepository->first();
        $player = $player->setFace($playerFace->id, $ageGroup->code);
        $this->playerRepository->save($player);

        // 背景画像を設定
        $background = $this->backgroundRepository->findByMoney($player->totalMoney);
        $player = $player->setBackgroundId($background->id);
        $this->playerRepository->save($player);

        return $player->id;
    }

    public function find(PlayerId $playerId): ?Player
    {
        return $this->playerRepository->find($playerId);
    }

    /**
     * @throws Exception
     */
    public function update(Player $player, EventResult $eventResult): void
    {
        // 結果に応じてステータスを更新する
        $player = $player->update(
            $eventResult->totalMoney,
            $eventResult->health,
            $eventResult->ability,
            $eventResult->evaluation,
        );

        // ターンを進める
        $player = $player->nextTurn();

        $this->playerRepository->save($player);

        // キャラ画像を更新する
        $this->updatePlayerFace($player);

        // 背景画像を更新する
        $this->updateBackground($player);
    }

    public function dead(Player $player): void
    {
        // プレイヤーを死亡状態にする
        $player = $player->dead();
        $this->playerRepository->save($player);
    }

    /**
     * @throws Exception
     */
    private function updatePlayerFace(Player $player): void
    {
        // 年代に応じてキャラ画像を変更する
        $newAgeGroup = $this->ageGroupRepository->findByAge($player->turn);
        if ($newAgeGroup === null) {
            return;
        }
        if ($newAgeGroup->code->equals($player->ageGroupCode)) {
            return;
        }
        $imgUrl = $this->dify->createPlayerNextImage($player);
        $img = Utility::getPngCompressedImage($imgUrl);
        $playerFace = $this->playerFaceFactory->create($player->id, $img);
        $this->playerFaceRepository->save($playerFace);

        $player = $player->setFace($playerFace->id, $newAgeGroup->code);
        $this->playerRepository->save($player);
    }

    private function updateBackground(Player $player): void
    {
        // 総資産に応じて背景画像を変更する
        $newBackground = $this->backgroundRepository->findByMoney($player->totalMoney);
        if ($newBackground === null) {
            return;
        }
        if ($newBackground->id->equals($player->backgroundId)) {
            return;
        }
        $player = $player->setBackgroundId($newBackground->id);
        $this->playerRepository->save($player);
    }
}
