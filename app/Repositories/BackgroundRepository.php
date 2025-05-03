<?php

namespace App\Repositories;

use App\Entities\Background;
use App\Mappers\BackgroundMapper;
use App\ValueObjects\BackgroundId;
use Illuminate\Support\Facades\DB;

final readonly class BackgroundRepository
{
    public function __construct(
        private BackgroundMapper $mapper,
    ) {
    }

    public function find(BackgroundId $backgroundImageId): ?Background
    {
        $model = DB::selectOne('SELECT * FROM background WHERE bg_id = ?', [$backgroundImageId->value]);
        if ($model === null) {
            return null;
        }
        if ($model->bg_img === null) {
            return null;
        }
        return $this->mapper->toEntity($model);
    }
}
