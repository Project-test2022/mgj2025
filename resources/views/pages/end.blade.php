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
            align-items: center;
            justify-content: center;
        }

        .header {
            font-size: 24px;
            color: #333;
            letter-spacing: 5px;
            margin-bottom: 20px;
            align-self: flex-start;
        }

        .main-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 90%;
            max-width: 1000px;
        }

        .main-area {
            position: relative;
            width: 1124px;
            height: 505px;
            background: url('{{ route('background', ['id' => $player->backgroundId ?? 0]) }}') no-repeat center center;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .panel {
            position: absolute;
            top: calc(50% - 285px); /* ←画像の中央よりちょい上に設定（微調整できる） */
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            height: 415px;
            background: rgba(255, 255, 255, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 36px;
            padding: 20px;
            z-index: 1;
        }

        .character-frame {
            position: relative;
            width: 240px;
            height: 300px;
            background: url('{{ asset('images/image.png?v='.config('app.version')) }}') no-repeat center center;
            background-size: cover;
        }

        .character-frame img {
            position: absolute;
            top: 8%;
            left: 16%;
            width: 66%;
            height: 83%;
            object-fit: cover;
        }

        .profile-info {
            width: 700px;
            height: 255px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            font-size: 22px;
            text-align: left;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            mix-blend-mode: multiply;
        }

        .question {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .buttons {
            display: flex;
            gap: 80px;
            margin-top: 40px;
        }

        .button {
            background: rgba(0, 0, 0, 0.7);
            color: white;
            width: 360px;
            height: 80px;
            font-size: 22px;
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
        <div class="main-area"></div>
        <div class="panel">
            <div class="character-frame">
                <img src="{{ route('face', ['id' => $player->playerFaceId ?? 0]) }}" alt="人物写真">
            </div>
            <div class="profile-info">
                <div class="question">「この人生は幸せでしたか？」</div>
                {{ $player->name->value }} {{ $player->sexName->value }}性　{{ $player->turn->value }}歳<br>
                資産：¥{{ number_format($player->totalMoney->value) }}<br>
            </div>
        </div>

        <form action="{{ route('start') }}" method="POST">
            @csrf
            <div class="buttons">
                <button type="button" class="button" onclick="location.href='{{ route('title') }}'">幸せでした</button>
                <button type="submit" class="button">もう一回</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // BGM の設定
            const bgm = new Audio('{{ asset('sounds/negative/beautiful-ruin.mp3') }}');
            bgm.loop = true;
            bgm.volume = 0.3; // 最初のクリックでBGM再生（自動再生対策）
            document.body.addEventListener('click', function playBgmOnce() {
                bgm.play().catch(err => console.log('BGM再生エラー:', err));
                document.body.removeEventListener('click', playBgmOnce);
            });
        });
    </script>
@endpush