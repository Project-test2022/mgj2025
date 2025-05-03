<?php

namespace App\Mappers;

use App\Entities\Background;
use App\ValueObjects\BackgroundId;
use App\ValueObjects\BackgroundName;

final readonly class BackgroundMapper
{
    public function toEntity($model): Background
    {
        $image = $model->bg_img;

        if (is_resource($image)) {
            $image = stream_get_contents($image);
        }
        return new Background(
            BackgroundId::from($model->bg_id),
            BackgroundName::from($model->bg_nm),
            $image,
        );
    }
}
