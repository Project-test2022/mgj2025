<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string      $player_id
 * @property string      $player_name
 * @property string      $sex_nm
 * @property string      $birth_year
 * @property int         $turn
 * @property int         $total_money
 * @property int         $health
 * @property int         $a_intelligence
 * @property int         $a_sport
 * @property int         $a_visual
 * @property int         $e_business
 * @property int         $e_love
 * @property string|null $bg_id
 * @property string|null $player_face_id
 */
class PlayerView extends Model
{
    protected $table = 'v_player';
    public $timestamps = false;
    protected $primaryKey = 'player_id';
    protected $keyType = 'string';
}
