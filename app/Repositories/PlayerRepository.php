<?php

namespace App\Repositories;

use App\Entities\Player;
use App\Mappers\PlayerMapper;

final readonly class PlayerRepository
{
    public function __construct(
        private PlayerMapper $mapper,
    ) {
    }

    public function save(Player $player): void
    {
        // FIXME: DBがないので、DBに保存できない
        // $model = $this->mapper->toModel($player);
        // $model->save();
    }
}
