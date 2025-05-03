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
            width: 100%;
            aspect-ratio: 16 / 9;
            background: url('資産に応じて生成された画像配置箇所') no-repeat center center;
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
            width: 200px;
            height: 260px;
            background: url('ai生成によって作成された人物画像配置箇所') no-repeat center center;
            background-size: cover;
            border: 1px solid #ccc;
        }

        .profile-info {
            background: rgba(0, 0, 0, 0.7);
            mix-blend-mode: multiply; /* 乗算モード */
            color: white;
            padding: 20px;
            font-size: 18px;
            line-height: 2;
            border-radius: 8px;
            text-align: left;
            min-width: 300px;
        }

        .buttons {
            display: flex;
            gap: 80px;
            margin-top: 20px;
        }

        .button {
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 15px 50px;
            font-size: 20px;
            border: none;
            border-radius: 5px;
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
        <div class="profile-area">
            <div class="character-image"></div>
            <div class="profile-info">
                {{ $player->name }} {{ $player->sexName }}性　{{ $player->age() }}歳<br>
                資産：¥{{ number_format($player->total_money->value) }}<br>
                知能：{{ $player->ability->intelligence }}　運動：{{ $player->ability->sport }}　容姿：{{ $player->ability->visual }}　健康：{{ $player->health }}
            </div>
        </div>

        <div class="buttons">
            <button class="button">仕事</button>
            <button class="button">恋愛</button>
        </div>
    </div>
@endsection
