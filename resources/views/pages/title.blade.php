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

        .header {
            width: 100%;
            max-width: 1124px;
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .container {
            text-align: center;
            padding: 40px 60px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            background-color: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(2px);
            margin-bottom: 48px;
        }

        .title {
            font-size: 32px;
            letter-spacing: 12px;
            color: #333;
            margin-bottom: 24px;
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
            width: 600px;
		    height: 40px;
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
        
        .form-label {
		    display: inline-block;
		    width: 105px;
		    height: 19px;
        }
        
        .form-input {
		    display: inline-block;
		    width: 270px;
		    height: 30px;
        }
        
		.form-group {
		    margin-bottom: 15px;
		}
		
		.form-description {
		    margin-bottom: 24px;
		}
        .bgm-message {
            color: red;
            font-weight: bold;
            margin-right: 10px;
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0;
            right: 0; bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px; width: 18px;
            left: 3px; bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #66bb6a;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }
    </style>
@endpush

@section('content')
    <div class="header">
        <button id="bgm-toggle" style="background: none; border: none; margin-left: 10px; cursor: pointer;"
            class="dont-loading">
            <img id="bgm-icon" src="{{ asset('icon/gray_off.png') }}" alt="BGMアイコン" width="24" height="24">
        </button>
        <label class="toggle-switch">
            <input type="checkbox" id="bgm-toggle-checkbox">
            <span class="slider"></span>
        </label>
        <span id="bgm-message">音ありでゲームを楽しみますか？</span>
    </div>
    <form action="{{ route('start') }}" method="POST">
        @csrf
        <div class="container">
            <h1 class="title">人生やり直しゲーム</h1>
            <span class="form-description">次に生まれる人生を選択・入力してください。</span>
            <div id="game-form">
                <div class="form-group">
                    <label for="name" class="form-label">名前　　　　:</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-input">

                    <input type="checkbox" name="name_random" id="toggle1" class="image-checkbox">
                    <label for="toggle1" class="checkbox-label">
                        <img src="{{ asset('images/select.png') }}" alt="OFF" class="off">
                        <img src="{{ asset('images/random.png') }}" alt="ON" class="on">
                    </label>
                </div>

                <div class="form-group">
                    <label for="birth_year" class="form-label">生まれ年　　:</label>
                    <input type="number" name="birth_year" class="form-input" value="{{ old('birth_year', 2000) }}">

                    <input type="checkbox" name="birth_year_random" id="toggle2" class="image-checkbox">
                    <label for="toggle2" class="checkbox-label">
                        <img src="{{ asset('images/select.png') }}" alt="OFF" class="off">
                        <img src="{{ asset('images/random.png') }}" alt="ON" class="on">
                    </label>
                </div>

                <div class="form-group">
                    <label for="gender" class="form-label">性別　　　　:</label>
                    <select name="gender" class="form-input">
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
    <script src="{{ asset('js/title.js?v='.config('app.version')) }}"></script>
    <script src="{{ asset('js/bgm.js') }}"></script>
    <script>
         function updateBgmIcon() {
            const icon = document.getElementById('bgm-icon');
            const enabled = localStorage.getItem('bgm_enabled') === 'true';
            icon.src = enabled
                ? '{{ asset('icon/red_on.png') }}'
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

            message = document.getElementById('bgm-message');
            setupBgm('{{ asset('sounds/choice/the-decision.mp3') }}');

            const checkbox = document.getElementById('bgm-toggle-checkbox');
            checkbox.addEventListener('change', () => {
                toggleBgm();
                updateBgmIcon();
            });

            const enabled = localStorage.getItem('bgm_enabled') === 'true';
            if (enabled) {
                bgm.play().catch(err => console.log('BGM再生失敗:', err));
                message.style.display = "音ありでゲームを楽しみます";
                message.style.color = 'red';
            }
            else
            {
                message.style.display = "音ありでゲームを楽しみますか？";
                message.style.color = 'gray';
            }
        });
    </script>
@endpush
