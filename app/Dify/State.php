<?php

namespace App\Dify;

enum State: int
{
    // キャラ生成
    case PLAYER_GENERATION = 0;
    // キャラ画像生成
    case PLAYER_IMAGE_GENERATION = 1;
    // イベント発生
    case EVENT_OCCURRENCE = 10;
    // イベント選択
    case EVENT_SELECTION = 20;
    // キャラの次の画像生成
    case PLAYER_NEXT_IMAGE_GENERATION = 30;
}
