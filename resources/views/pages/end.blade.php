@extends('layouts.parent')

@push('styles')
    <style>

        .image{
            /* background: url('{{ route('background', ['id' => $player->backgroundId ?? 0]) }}') no-repeat center center; */
            background: url("../images/home4.png") no-repeat center center;
        }

        .character-stack{
            position: relative;
            width: 300px;
            height: 350px;
        }

        .character-image, .character-frame{
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            /* 同じ大きさにするなら width/height:100% など指定 */
            width: 100%;
            height: 100%;
        }

        .character-image{

            background: url('{{ route('face', ['id' => $player->playerFaceId ?? 0]) }}') no-repeat center center;
            width: 85%;
            height: 85%;

            background-color: white;
        }

        .character-frame {
            background: url('{{ asset('images/ieiflame.png?v='.config('app.version')) }}') no-repeat center center;
        }

        .black-background{
            background-color: rgba(0, 0, 0, 0.75);
            mix-blend-mode: multiply;       /* 乗算モード */
            border: 1px solid rgba(0, 0, 0);

            letter-spacing: 0.1em;
            line-height: 1.7;

            display: flex;        /* Flex コンテナ化 */
            justify-content: center;      /* 水平方向（メイン軸）中央 */
            align-items: center;          /* 垂直方向（交差軸）中央 */

            width: 80%;
            height: 100%;
        }

        .profile-info{
            color: rgb(255, 255, 255);
            margin: 1em 3em;

            width: 80%;
            /* height: 100px; */
        }

        .question {
            font-size: 1em;
            font-weight: bold;

            transform: translateX(-0.5em);
        }

        .button{
            letter-spacing: 0.3em;
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
        <div class="information">
            <!-- 縦詰み要素のためのdiv -->
            <div class="stacking-image-header">
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
                <form action="{{ route('start') }}" method="POST">
                    @csrf
                    <input type="hidden" name="name" value="{{ $player->name }}">
                    <input type="hidden" name="gender" value="{{ $sexCode }}">
                    <input type="hidden" name="birth_year" value="{{ $player->birthYear }}">
                    <div class="buttons">
                        <button type="button" class="button" onclick="location.href='{{ route('title') }}'">幸せでした</button>
                        <button type="submit" class="button">もう一回</button>
                    </div>
                </form>

            </div>

            <!-- 背景の白い帯 -->
            <div class="white-panel"></div>

            <!-- プロフィールエリア -->
            <div class ="contents">
                <!-- キャラクター画像 -->
                <div class="character-stack">
                    <div class="character-image"></div>
                    <div class="character-frame"></div>
                </div>
                <!-- プロフィール情報 -->
                <div class="black-background">

                    <div id="main-profile" class="profile-info">
                        <div class="question">「この人生は幸せでしたか？」</div>
                        <p>
                        {{ $player->name->value }}　　{{ $player->sexName->value }}性　　{{ $player->turn->value }}歳<br>
                        資産：{{ $player->totalMoney->format() }}<br>
                        </p>
                    </div>

                </div>
            </div>

        </div>

    </body>
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
            const btns = document.querySelectorAll('.buttons');
            const se = new Audio('{{ asset('sounds/se/decide-button-a.mp3') }}');
            se.volume = 0.3;
            btns.forEach(btn => {
                if (btn.textContent.length >= 6) {
                    btn.classList.add('long-text');
                }
            });

            // 初期再生
            setupBgm('{{ asset('sounds/negative/beautiful-ruin.mp3') }}');
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
