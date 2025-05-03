<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $player_id
 * @property string $player_name
 * @property string $sex_cd
 * @property string $birth_date
 * @property int    $turn
 * @property int    $total_money
 * @property int    $health
 * @property int    $a_intelligence
 * @property int    $a_sport
 * @property int    $a_visual
 * @property int    $e_business
 * @property int    $e_love
 * @property string $bg_id
 */
class PlayerModel extends Model
{
    protected $table = 'player';
    public $timestamps = false;
    protected $primaryKey = 'player_id';
    protected $keyType = 'string';
}
