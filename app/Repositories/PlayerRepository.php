<?php

namespace App\Repositories;

use App\Entities\Player;
use App\Mappers\PlayerMapper;
use App\Models\PlayerView;
use App\ValueObjects\PlayerId;

final readonly class PlayerRepository
{
    public function __construct(
        private PlayerMapper $mapper,
    ) {
    }

    public function find(PlayerId $id): ?Player
    {
        $model = PlayerView::query()->find($id->value);
        if ($model === null) {
            return null;
        }
        return $this->mapper->toEntity($model);
    }

    public function save(Player $player): void
    {
         $model = $this->mapper->toModel($player);
         $model->save();
    }
}
