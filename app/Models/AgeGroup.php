<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $age_grp_cd
 * @property string $age_grp_nm
 */
class AgeGroup extends Model
{
    protected $table = 'age_grp';
    public $timestamps = false;
}
