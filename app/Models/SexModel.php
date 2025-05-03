<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $sex_cd
 * @property string $sex_nm
 */
class SexModel extends Model
{
    protected $table = 'sex';
    public $timestamps = false;
}
