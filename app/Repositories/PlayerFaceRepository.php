<?php

namespace App\Repositories;

use App\Entities\PlayerFace;
use App\Mappers\PlayerFaceMapper;
use App\ValueObjects\PlayerFaceId;
use Illuminate\Support\Facades\DB;

final readonly class PlayerFaceRepository
{
    public function __construct(
        private PlayerFaceMapper $mapper,
    ) {
    }

    public function save(PlayerFace $playerFace): void
    {
        DB::table('player_face')->insert([
            'player_face_id' => $playerFace->id->value,
            'player_id' => $playerFace->playerId->value,
            'age_grp_cd' => $playerFace->ageGroupCode->value,
            'face_img' => DB::raw("E'\\\\x" . bin2hex($playerFace->image) . "'"),
        ]);
    }

    public function find(PlayerFaceId $playerFaceId): ?PlayerFace
    {
        $model = DB::selectOne('SELECT * FROM player_face WHERE player_face_id = ?', [$playerFaceId->value]);
        if ($model === null) {
            return null;
        }
        return $this->mapper->toEntity($model);
    }
}
