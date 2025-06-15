@extends('layouts.parent')

@push('styles')
    <style>
        .image{
            /* --bg: url('{{ route('background', ['id' => $player->backgroundId ?? 0]) }}'); */
            background: url("../images/home4.png") no-repeat center center;
        }
        .character-image{
            background: url('{{ route('face', ['id' => $player->playerFaceId ?? 0]) }}') no-repeat center center;

            background-color: white;
            border: 1px solid rgba(46, 41, 41);
            width: 240px;
            height: 300px;
        }

        .toggle{
            display: flex;
            align-items: center;  /* 交差軸（縦）を中央に */

            gap: 0.6em;

            transform: translate(-13em, -2em);
            width: 300px;
        }

        .toggle-button {
            display: flex;
            align-items: center;
            position: relative;
            width: 2.5em;
            height: 1.2em;
            border-radius: 1em;
            box-sizing: content-box;
            background-color:rgb(67, 67, 67);
            cursor: pointer;
            transition: background-color .4s;
        }

        .toggle-button::before {
            position: absolute;
            left: 4px;
            width: 0.9em;
            height: 0.9em;
            border-radius: 50%;
            background-color: #c8c8c8;
            content: '';
            transition: left .4s;
        }

        .toggle-button:has(:checked)::before {
            left: 1.4em;
        }

        .toggle-button::after {
            position: absolute;
            left: 26px;
            transform: translateX(-50%);
            color: #fff;
            font-weight: 600;
            font-size: .9em;
            transition: left .4s;
        }

        .toggle-button input {
            display: none;
        }

        .black-background{
            background-color: rgba(0, 0, 0, 0.75);
            mix-blend-mode: multiply;       /* 乗算モード */
            border: 1px solid rgba(0, 0, 0);

            letter-spacing: 0.1em;
            line-height: 1.7;

            display: flex;        /* Flex コンテナ化 */
            align-items: center;  /* 交差軸（縦）を中央に */
            flex-direction: column;

            width: 80%;
            height: 100%;
        }

        .profile-info{
            color: rgb(255, 255, 255);
            margin: 1em 3em;

            width: 80%;
            /* height: 100px; */
        }

    </style>
@endpush

@section('content')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <head>
        <!-- 共通CSSの読込 -->
        <link
        rel="stylesheet"
        href="{{ asset('css/app.css') }}?v={{ config('app.version') }}">
    </head>
    <body>
        
        <!-- プロフィールエリア -->
        <div class="infomation">
            <!-- 縦詰み要素のためのdiv -->
            <div class="stacking-image_header">
                <!-- ヘッダー -->
                <div class="header">
                    <div class="header-left">
                        <div class="title">
                            人生やり直しゲーム
                        </div>
                        <button id="bgm-toggle" class="dont-loading">
                                <img id="bgm-icon" src="{{ asset('icon/gray_off.png') }}" alt="BGMアイコン">
                        </button>
                    </div>
                    <div class="turn">西暦：{{ $player->currentYear() }}年</div>
                </div>
                <!-- 家の背景 -->
                <div class="image"></div>
                <!-- ボタン -->
                <form action="{{ route('select', ['id' => $player->id->value]) }}" method="POST">
                    @csrf
                    <div class="buttons">
                        @foreach($actions as $action)
                            <button type="submit" name="action" value="{{ $action }}" class="button">{{ $action }}</button>
                        @endforeach
                    </div>
                </form>
            </div>
            
            <!-- 背景の白い帯 -->
            <div class="white-panel"></div>

            <!-- プロフィールエリア -->
            <div class ="contents">
                <!-- キャラクター画像 -->
                <div class="character-image"></div>
                <!-- プロフィール情報 -->
                <div class="black-background">
                    <!-- トグルボタン -->
                    <div class="toggle">
                        <label class="toggle-button">
                            <input id="toggleBtn" type="checkbox"/>
                        </label>
                        <div id="profile-or-ability" class="toggle-element">
                            プロフィール
                        </div>
                    </div>
                    <div id="main-profile" class="profile-info">
                        <p>
                        {{ $player->name }}({{ $player->turn->value }})　　{{ $player->sexName }}性　　職業: {{ $player->job?->value }}<br>
                        資産：{{ $player->totalMoney->format() }}　　年収：{{ $player->income->format() }}<br>
                        パートナー：【工事中】
                        </p>
                        <p>
                        仕事：{{ $player->evaluation->business }}　　幸福：{{ $player->evaluation->happiness }}　　健康：{{ $player->health }}<br>
                        知能：{{ $player->ability->intelligence }}　　運動：{{ $player->ability->sport }}
                        　　容姿：{{ $player->ability->visual }}　　感性：{{ $player->ability->sense }}
                        </p>
                    </div>
                    <div id="special-abilities" class="profile-info  hidden">
                        <p>
                        大声 , 麻雀が強い , 女装が似合う , 自分のことをかわいいと思っている , 会社で力が強い , 良い大学を出ている , 裕福な実家 , 慶応卒 , 心配性 , エネルギーが籠ったものが怖い
                        </p>
                    </div>
                </div>
            </div>
                
        </div>
        
    </body>
@endsection

@push('scripts')
    <!-- プロフィール切り替え -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('toggleBtn');
        const text = document.getElementById('profile-or-ability');

        const prof = document.getElementById('main-profile');
        const abil = document.getElementById('special-abilities');

        btn.addEventListener('click', () => {
            prof.classList.toggle('hidden');
            abil.classList.toggle('hidden');

            if (prof.classList.contains('hidden')) {
                text.textContent = '特殊能力';
                } else {
                text.textContent = 'プロフィール';
            }
        });
    });
    </script>

    <!-- BGM -->
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
