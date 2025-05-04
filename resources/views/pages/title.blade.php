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
            background-color: rgba(255, 255, 255, 0.4);
            border: none;
            letter-spacing: 5px;
            cursor: pointer;
            width: 100%;
        }

        .start-button:hover {
            background-color: rgba(255, 255, 255, 0.6);
        }
        .image-checkbox {
            display: none;
        }

        .checkbox-label {
            display: inline-block;
            cursor: pointer;
        }

        .checkbox-label img {
            width: 100px;
            height: auto;
        }

        .checkbox-label .off {
            display: block;
        }

        .checkbox-label .on {
            display: none;
        }

        .image-checkbox:checked + .checkbox-label .off {
            display: none;
        }

        .image-checkbox:checked + .checkbox-label .on {
            display: block;
        }
    </style>
@endpush

@section('content')
    <form action="{{ route('start') }}" method="POST">
        @csrf
        <div class="container">
            <h1 class="title">人生やり直しゲーム</h1>
            <span>次に生まれる人生を選択・入力してください。</span>
            <div id="game-form">
                <div class="form-group">
                    <label for="name">名  前</label>
                    <input type="text" name="name" value="{{ old('name') }}">

                    <input type="checkbox" name="name_random" id="toggle1" class="image-checkbox">
                    <label for="toggle1" class="checkbox-label">
                        <img src="{{ asset('images/select.png') }}" alt="OFF" class="off">
                        <img src="{{ asset('images/random.png') }}" alt="ON" class="on">
                    </label>
                </div>

                <div class="form-group">
                    <label for="birth_year">生まれ年</label>
                    <input type="number" name="birth_year" value="{{ old('birth_year', 2000) }}">

                    <input type="checkbox" name="birth_year_random" id="toggle2" class="image-checkbox">
                    <label for="toggle2" class="checkbox-label">
                        <img src="{{ asset('images/select.png') }}" alt="OFF" class="off">
                        <img src="{{ asset('images/random.png') }}" alt="ON" class="on">
                    </label>
                </div>

                <div class="form-group">
                    <label for="gender">性  別</label>
                    <select name="gender">
                        <option value disabled selected>--選択してください--</option>
                        @foreach($sexes as $sex)
                            <option value="{{ $sex->sex_cd }}" @selected(old('gender') == $sex->sex_cd)>{{ $sex->sex_nm }}</option>
                        @endforeach
                    </select>

                    <input type="checkbox" name="gender_random" id="toggle3" class="image-checkbox">
                    <label for="toggle3" class="checkbox-label">
                        <img src="{{ asset('images/select.png') }}" alt="OFF" class="off">
                        <img src="{{ asset('images/random.png') }}" alt="ON" class="on">
                    </label>
                </div>
            </div>
        </div>
        <button id="btn" type="submit" class="start-button">START</button>
        @if($errors->any())
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        @endif
    </form>
@endsection

@push('scripts')
    <script>
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
            // BGM 縺ｮ險ｭ螳
            const bgm = new Audio('{{ asset('sounds/choice/the-decision.mp3') }}');
            bgm.loop = true;
            bgm.volume = 0.3;
            document.body.addEventListener('click', function playBgmOnce() {
            bgm.play().catch(err => console.log('BGM再生失敗:', err));
            document.body.removeEventListener('click', playBgmOnce);
        });
    });
    </script>
@endpush
