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
            font-size: 24px;
            color: #333;
            letter-spacing: 5px;
            margin-bottom: 20px;
            align-self: flex-start;
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
            top: calc(50% - 285px);
            left: 50%;
            transform: translateX(-50%);
            width: 100%; /* ←ブラウザ幅いっぱいにする */
            height: 415px;
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
            width: 700px;
            height: 300px;
            color: white;
            font-size: 22px;
            line-height: 4;
            text-align: left;
            min-width: 600px;
            padding-left: 10px;
            mix-blend-mode: multiply; /* 乗算モード */
        }

        .buttons {
            display: flex;
            gap: 80px;
            margin-top: 20px;
        }

        .button {
            background: rgba(0, 0, 0, 0.7);
            mix-blend-mode: normal;
            color: white;
            width: 360px;
            height: 80px;
            padding: 15px 50px;
            font-size: 20px;
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
        <div class="header">人生やり直しゲーム</div>
        <div class="profile-area"></div>
        <div class='panel'>
            <div class="character-image"></div>
            <div class="profile-info">
                {{ $player->name }} {{ $player->sexName }}性　{{ $player->turn->value }}歳<br>
                資産：{{ $player->totalMoney->format() }}<br>
                知能：{{ $player->ability->intelligence }} 運動：{{ $player->ability->sport }}
                容姿：{{ $player->ability->visual }} 健康：{{ $player->health }}
                仕事: {{ $player->evaluation->business }} 恋愛: {{ $player->evaluation->love }}
            </div>
        </div>

        <form action="{{ route('select', ['id' => $player->id->value]) }}" method="POST">
            @csrf
            <div class="buttons">
                <button type="submit" name="business" class="button">仕事</button>
                <button type="submit" name="love" class="button">恋愛</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 効果音（ボタン用）
            const btn = document.querySelectorAll('.buttons');
            const se = new Audio('{{ asset('sounds/se/decide-button-a.mp3') }}');
            se.volume = 0.3;
            btn.addEventListener('click', function () {
                se.currentTime = 0;
                se.play(); 
            });
            // BGM の設定
            const bgm = new Audio('{{ asset('sounds/choice/high-stakes-shadow.mp3') }}');
            bgm.loop = true;
            bgm.volume = 0.3; 
            bgm.play().then(() => {
              setTimeout(() => {
                  bgm.muted = false;
              }, 500); // 0.5秒後に再生
            }).catch(err => {
                console.log('自動再生失敗:', err);
            });
        });
    </script>
@endpush