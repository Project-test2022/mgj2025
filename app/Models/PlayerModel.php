<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int    $id
 * @property int    $turn
 * @property int    $birth
 * @property int    $sex
 * @property int    $money
 * @property string $name
 * @property int    $health
 * @property int    $a_intelligence
 * @property int    $a_sport
 * @property int    $e_business
 * @property int    $e_love
 */
class PlayerModel extends Model
{
    public $timestamps = false;
}
