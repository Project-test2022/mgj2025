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
        @if($errors->any())
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        @endif
        <form id="game-form" action="{{ route('start') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">名前</label>
                <input type="text" name="name" value="{{ old('name') }}">
                <label><input type="checkbox" name="name_random" @checked(old('name_random', false))> ランダム</label>
            </div>

            <div class="form-group">
                <label for="birth_year">生年（西暦）</label>
                <input type="number" name="birth_year" value="{{ old('birth_year', 2000) }}">
                <label><input type="checkbox" name="birth_year_random" @checked(old('birth_year_random', false))> ランダム</label>
            </div>

            <div class="form-group">
                <label for="gender">性別</label>
                <select name="gender">
                    <option value disabled selected>--選択してください--</option>
                    @foreach($sexes as $sex)
                        <option value="{{ $sex->sex_nm }}" @selected(old('gender') == $sex->sex_nm)>{{ $sex->sex_nm }}</option>
                    @endforeach
                </select>
                <label><input type="checkbox" name="gender_random" @checked(old('gender_random', false))>ランダム</label>
            </div>

            <button id="btn" type="submit" class="start-button">START</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 効果音（ボタン用）
            const btn = document.getElementById('btn');
            const se = new Audio('{{ asset('sounds/se/decide-button-a.mp3') }}');
            se.volume = 1.0;
            btn.addEventListener('click', function () {
                se.currentTime = 0;
                se.play();
            });
            // BGM の設定
            const bgm = new Audio('{{ asset('sounds/op/high-stakes-shadow.mp3') }}');
            bgm.loop = true;
            bgm.muted = true;
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
