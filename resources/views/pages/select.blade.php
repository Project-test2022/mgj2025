@extends('layouts.parent')

@push('styles')
    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: 'Arial', sans-serif;
            background: url('{{ asset('images/background.png?v='.config('app.version')) }}') no-repeat center center;
            background-size: cover;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .main-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header {
            width: 100%;
            max-width: 1280px;
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .turn {
            font-size: 20px;
            padding: 5px;
        }

        .event-area {
            width: 1124px;
            height: 505px;
            background: url('{{ asset('images/crossroadsinlife.png?v='.config('app.version')) }}') no-repeat center center;
            background-size: cover;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 40px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(2px);
        }

        .panel {
            background: rgba(255, 255, 255, 0.4);
            width: 100%;
            height: 415px;
            top: calc(50% - 250px);
            left: 50%;
            transform: translateX(-50%);
            padding: 10px;
            position: absolute;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 36px;
        }

        .event-text {
            width: 700px;
            height: 300px;
            padding: 15px 25px;
            color: #070606;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            position: absolute;
            top: 40%;
        }

        .buttons {
            display: flex;
            gap: 30px;
            margin-top: 40px;
        }

        .button {
            width: 548px;
            height: 80px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            font-size: 22px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            letter-spacing: 5px;
            transition: background 0.3s;
        }

        .button:hover {
            background: rgba(0, 0, 0, 0.9);
        }
    </style>
@endpush

@section('content')
<div class="main-wrapper">
    <div class="header">
      <div class="title">
        人生やり直しゲーム
        <button id="bgm-toggle" style="background: none; border: none; margin-left: 10px; cursor: pointer;" class="dont-loading">
            <img id="bgm-icon" src="{{ asset('icon/gray_off.png') }}" alt="BGMアイコン" width="24" height="24">
        </button>
    </div>
      <div class="turn">西暦：{{ $player->currentYear() }}</div>
    </div>
    <div class="event-area"></div>
    <div class="panel">
        <div class="event-text">
            {{ $event->content }}
        </div>
    </div>

    <form action="{{ route('event.select', ['id' => $player->id]) }}" method="POST">
        @csrf
        <input type="hidden" name="action" value="{{ $action }}">
        <input type="hidden" name="event" value="{{ $event->content }}">
        <input type="hidden" name="choice1" value="{{ $event->choice1->content }}">
        <input type="hidden" name="rate1" value="{{ $event->choice1->rate }}">
        <input type="hidden" name="choice2" value="{{ $event->choice2->content }}">
        <input type="hidden" name="rate2" value="{{ $event->choice2->rate }}">
        <div class="buttons">
            <button type="submit" name="ok" class="button">{{ $event->choice1->content }} (成功率 {{ $event->choice1->rate }}%)</button>
            <button type="submit" name="ng" class="button">{{ $event->choice2->content }} (成功率 {{ $event->choice2->rate }}%)</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/bgm.js') }}"></script>
    <script>
        function updateBgmIcon() {
            const icon = document.getElementById('bgm-icon');
            const enabled = localStorage.getItem('bgm_enabled') === 'true';
            icon.src = enabled
                ? '{{ asset('icon/gray_on.png') }}'
                : '{{ asset('icon/gray_off.png') }}';
        }

        document.addEventListener('DOMContentLoaded', function () {
            // 効果音（ボタン用）
            const btns = document.querySelectorAll('.buttons');
            const se = new Audio('{{ asset('sounds/se/decide-button-a.mp3') }}');
            se.volume = 0.3;
            btns.forEach(btn => {
                btn.addEventListener('click', function () {
                    se.currentTime = 0;
                    se.play();
                });
            });

            // BGM の設定
            // 初期再生
            setupBgm('{{ asset('sounds/op/high-stakes-shadow.mp3') }}');
            // 初期アイコン
            updateBgmIcon();

            // ボタンクリックで切り替え
            document.getElementById('bgm-toggle').addEventListener('click', function () {
                toggleBgm();
                updateBgmIcon();
            });

            // 初期再生
            const enabled = localStorage.getItem('bgm_enabled') === 'true';
            if (enabled) {
                bgm.play().catch(err => console.log('BGM再生失敗:', err));
            }
        });
    </script>
@endpush
