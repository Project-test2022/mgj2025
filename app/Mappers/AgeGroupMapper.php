<?php

namespace App\Mappers;

use App\Entities\AgeGroup;
use App\Models\AgeGroupModel;
use App\ValueObjects\AgeGroupCode;
use App\ValueObjects\AgeGroupName;

final readonly class AgeGroupMapper
{
    public function toEntity(AgeGroupModel $model): AgeGroup
    {
        return new AgeGroup(
            AgeGroupCode::from($model->age_grp_cd),
            AgeGroupName::from($model->age_grp_nm),
        );
    }
}
