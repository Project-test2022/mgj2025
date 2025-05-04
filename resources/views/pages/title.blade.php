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
    <form id="game-form" action="{{ route('start') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">名前</label>
            <input type="text" name="name" id="name" maxlength="20">
            <label><input type="checkbox" id="name_random"> ランダム</label>
        </div>

        <div class="form-group">
            <label for="birth_year">生年（西暦）</label>
            <input type="number" name="birth_year" id="birth_year" min="0" max="99999" value="2000">
            <label><input type="checkbox" id="birth_random"> ランダム</label>
        </div>

        <div class="form-group">
            <label for="gender">性別</label>
            <select name="gender" id="gender">
                <option value="">--選択してください--</option>
                <option value="male">男性</option>
                <option value="female">女性</option>
                <option value="other">その他</option>
            </select>
            <label><input type="checkbox" id="gender_random"> ランダム</label>
        </div>

        <button id="btn" type="submit" class="start-button">START</button>
    </form>
</div>

<script>
document.getElementById('game-form').addEventListener('submit', function (e) {
    const name = document.getElementById('name').value.trim();
    const birthYear = document.getElementById('birth_year').value.trim();
    const gender = document.getElementById('gender').value;
    const nameRand = document.getElementById('name_random').checked;
    const birthRand = document.getElementById('birth_random').checked;
    const genderRand = document.getElementById('gender_random').checked;

    // バリデーション：未入力かつランダム未チェックならエラー
    let errors = [];

    if (!name && !nameRand) {
        errors.push("名前を入力するか、ランダムにチェックしてください。");
    }
    if (!birthYear && !birthRand) {
        errors.push("生年を入力するか、ランダムにチェックしてください。");
    }
    if (!gender && !genderRand) {
        errors.push("性別を選択するか、ランダムにチェックしてください。");
    }

    // 生年が6桁以上ならエラー
    if (birthYear.length > 5) {
        errors.push("生年は5桁以内で入力してください。");
    }

    if (errors.length > 0) {
        e.preventDefault();
        alert(errors.join("\n"));
    }
});
</script>
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
