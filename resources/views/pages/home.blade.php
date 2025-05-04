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
            justify-content: center;
            align-items: center;
        }

        .main-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 90%;
            max-width: 1000px;
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

        .profile-area {
            position: relative;
            width: 1124px;
            height: 505px;
            background: url('{{ route('background', ['id' => $player->backgroundId ?? 0]) }}') no-repeat center center;
            background-size: cover;
            border: 1px solid rgba(255, 255, 255, 0.3);
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 40px;
            padding: 20px;
        }

        .character-image {
            width: 240px;
            height: 300px;
            background: url('{{ route('face', ['id' => $player->playerFaceId ?? 0]) }}') no-repeat center center;
            background-size: cover;
            border: 1px solid #ccc;
            margin-left: 155px;
        }

        .panel {
            position: absolute;
            top: calc(50% - 214px);
            width: 100%;
            padding: 10px;
            background: rgba(255, 255, 255, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 36px;
            z-index: 1;
        }

        .profile-info {
            background: rgba(0, 0, 0, 0.70);
            mix-blend-mode: multiply;       /* 乗算モード */
            width: 700px;
            height: 300px;
            color: white;
            font-size: 22px;
            padding-left: 55px;
            line-height: 2;
            display: flex;
            margin-right: 135px;
            flex-direction: column;
            justify-content: center;
        }

        .buttons {
            display: flex;
            gap: 31px;
            margin-top: 30px;
        }

        .button {
            background: rgba(0, 0, 0, 0.7);
            color: white;
            width: 210px;
            height: 80px;
            font-size: 22px;
            text-align: center;
            border: none;
            cursor: pointer;
            letter-spacing: 3px;
            transition: background 0.3s;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .button {
            font-size: 16px;
            line-height: 1.2;
            flex-direction: column;
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
            <div class="turn">西暦：{{ $player->currentYear() }}年</div>
        </div>
        <div class="profile-area"></div>
        <div class='panel'>
            <div class="character-image"></div>
            <div class="profile-info">
                {{ $player->name }}({{ $player->turn->value }})　　{{ $player->sexName }}性　　職業: {{ $player->job?->value }}<br>
                資産：{{ $player->totalMoney->format() }}　　年収：{{ $player->income->format() }}<br>
                仕事：{{ $player->evaluation->business }}　　幸福：{{ $player->evaluation->happiness }}　　健康：{{ $player->health }}<br>
                知能：{{ $player->ability->intelligence }}　　運動：{{ $player->ability->sport }}
                　　容姿：{{ $player->ability->visual }}　　感性：{{ $player->ability->sense }}
            </div>
        </div>

        <form action="{{ route('select', ['id' => $player->id->value]) }}" method="POST">
            @csrf
            <div class="buttons">
                @foreach($actions as $action)
                    <button type="submit" name="action" value="{{ $action }}" class="button">{{ $action }}</button>
                @endforeach
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
            document.querySelectorAll('.button').forEach(button => {
                if (button.textContent.length >= 6) {
                    button.classList.add('long-text');
                }
            });

            const btns = document.querySelectorAll('.buttons');
            const se = new Audio('{{ asset('sounds/se/decide-button-a.mp3') }}');
            se.volume = 0.3;
            btns.forEach(btn => {
                btn.addEventListener('click', function () {
                    se.currentTime = 0;
                    se.play();
                });
            });

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
