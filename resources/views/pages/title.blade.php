@extends('layouts.parent')

@push('styles')
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
            background: url('{{ asset('images/background.png?v='.config('app.version')) }}') no-repeat center center;
            background-size: cover;
        }

        .container {
            text-align: center;
            padding: 40px 60px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            background-color: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(2px);
        }

        .title {
            font-size: 32px;
            letter-spacing: 12px;
            color: #333;
            margin-bottom: 30px;
        }

        .start-button {
            display: inline-block;
            padding: 10px 80px;
            font-size: 18px;
            color: #333;
            background-color: rgba(255, 255, 255, 0.2);
            border: none;
            letter-spacing: 5px;
            cursor: pointer;
        }

        .start-button:hover {
            background-color: rgba(255, 255, 255, 0.4);
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="title">人生やり直しゲーム</div>
        <form action="{{ route('start') }}" method="POST">
            @csrf
            <button id="btn" type="submit" class="start-button">START</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 効果音（ボタン用）
            const btn = document.getElementById('btn');
            const se = new Audio('{{ asset('sounds/choice/the-decision.mp3') }}');// todo:素材差し替え
            se.volume = 1.0;
            btn.addEventListener('click', function () {
                se.currentTime = 0;
                se.play();
            });
            // BGM の設定
            const bgm = new Audio('{{ asset('sounds/op/high-stakes-shadow.mp3') }}');
            bgm.loop = true;
            bgm.volume = 0.3; // 最初のクリックでBGM再生（自動再生対策）
            document.body.addEventListener('click', function playBgmOnce() {
                bgm.play().catch(err => console.log('BGM再生エラー:', err));
                document.body.removeEventListener('click', playBgmOnce);
            });
        });
    </script>
@endpush
