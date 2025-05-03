<?php

namespace App\Mappers;

use App\Entities\PlayerFace;
use App\ValueObjects\AgeGroupCode;
use App\ValueObjects\PlayerFaceId;
use App\ValueObjects\PlayerId;

final readonly class PlayerFaceMapper
{
    public function toEntity($model): PlayerFace
    {
        return new PlayerFace(
            PlayerFaceId::from($model->player_face_id),
            PlayerId::from($model->player_face_id),
            AgeGroupCode::from($model->age_grp_cd),
            $model->face_img,
        );
    }
}
