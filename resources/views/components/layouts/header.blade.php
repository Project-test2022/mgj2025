@props([
    'player',
])

<div class="header">
    <div class="header-left">
        <div class="title">人生やり直しゲーム</div>
        <button id="bgm-toggle" class="dont-loading">
            <img id="bgm-icon" src="" alt="BGMアイコン">
        </button>
    </div>
    <div class="turn">西暦：{{ $player->currentYear() }}年</div>
</div>
