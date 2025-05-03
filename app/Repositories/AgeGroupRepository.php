<?php

namespace App\Repositories;

use App\Entities\AgeGroup;
use App\Mappers\AgeGroupMapper;
use App\Models\AgeGroupModel;
use App\ValueObjects\Turn;

final readonly class AgeGroupRepository
{
    public function __construct(
        private AgeGroupMapper $mapper,
    ) {
    }

    public function findByAge(Turn $age): ?AgeGroup
    {
        $model = AgeGroupModel::query()
            ->where(function($q) use ($age) {
                $q->whereNull('border_age_s')
                    ->orWhere('border_age_s', '<=', $age->value);
            })
            ->where(function($q) use ($age) {
                $q->whereNull('border_age_e')
                    ->orWhere('border_age_e', '>=', $age->value);
            })
            ->first();

        if ($model === null) {
            return null;
        }
        return $this->mapper->toEntity($model);
    }

    public function first(): AgeGroup
    {
        $model = AgeGroupModel::query()->first();
        return $this->mapper->toEntity($model);
    }
}
