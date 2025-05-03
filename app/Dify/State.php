<?php

namespace App\Dify;

enum State: int
{
    // キャラ生成
    case PLAYER_GENERATION = 0;
    // イベント発生
    case EVENT_OCCURRENCE = 10;
    // イベント選択
    case EVENT_SELECTION = 20;
}
