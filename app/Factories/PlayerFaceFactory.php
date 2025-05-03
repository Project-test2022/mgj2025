<?php

namespace App\Factories;

use App\Entities\PlayerFace;
use App\Models\AgeGroupModel;
use App\ValueObjects\AgeGroupCode;
use App\ValueObjects\PlayerFaceId;
use App\ValueObjects\PlayerId;
use Illuminate\Support\Facades\DB;

final readonly class PlayerFaceFactory
{
    public function create(PlayerId $id,  string $image): PlayerFace
    {
        $age_grp_cd = AgeGroupModel::query()->first()->age_grp_cd;
        return new PlayerFace(
            $this->generateId(),
            $id,
            AgeGroupCode::from($age_grp_cd),
            $image,
        );
    }

    public function generateId(): PlayerFaceId {
        do {
            $id = uniqid();
        } while (DB::table('player_face')->where('player_face_id', $id)->exists());

        return PlayerFaceId::from($id);
    }
}
