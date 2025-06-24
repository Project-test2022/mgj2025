@extends('layouts.parent')

@push('styles')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        .image{
            background: url('{{ asset('images/crossroadsinlife.png?v='.config('app.version')) }}') no-repeat center center;
        }

        .event-area {
            width: 1124px;
            height: 505px;
            @if($result->success)
                    background: url('{{ asset('images/choice_success.jpg?v='.config('app.version')) }}') no-repeat center center;
            @else
                    background: url('{{ asset('images/choice_lose.jpg?v='.config('app.version')) }}') no-repeat center center;
            @endif
                background-size: cover;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 40px;
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .contents{
            justify-content: center;
            align-items: center;
        }

        .event-text{
            text-align: center;
            color: rgba(35, 35, 35);
        }

        .event-button {
            width: 100%;              /* 親要素幅いっぱい */
            height:54px;
            margin-top: 1em;

            display: flex;
            justify-content: center;
            align-items: center;

            padding: 14px 0;          /* 縦にゆとりを持たせる */
            font-size: 1em;          /* 基本フォントサイズ */
            color: white;           /* 文字色 */
            background: rgba(0, 0, 0, 0.7);/* 背景色 */
            border: 1px solid rgba(46, 41, 41);

            cursor: pointer;
            transition:
                background-color 0.2s,
                color 0.2s,
                box-shadow 0.2s;

            text-align: center;

        }

        .event-button:hover {
            background: rgba(0, 0, 0, 0.9);
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
                @if($result->dead)
                    <button id="button-happy" class="event-button"
                            onclick="location.href='{{ route('result', ['id' => $player->id]) }}'">
                            おわり
                    </button>
                @else
                    <button class="event-button"
                            onclick="location.href='{{ route('home', ['id' => $player->id]) }}'">
                            <img src="{{ asset('icon/let_go_home.png') }}"/>
                    </button>
                @endif

            </div>

            <!-- 背景の白い帯 -->
            <div class="white-panel"></div>

            <!-- イベントテキストエリア -->
            <div class ="contents">

                <!-- イベントテキスト -->
                <div class="event-text">
                <p>
                <b>- {{ $result->result() }} -</b><br>
                </p>
                <p>
                @if(!empty($result->message))
                    {{ $result->message }}
                @endif
                </p>
                <p>
                @if($result->totalMoney->value !== 0)
                    資産：{{ $result->money() }}<br>
                @endif
                @if($newJob)
                    職業：{{ $newJob }} New!
                @endif
                @if($incomeDiff)
                    年収：{{ $incomeDiff->format(true) }}<br>
                @endif
                @if($result->ability->intelligence->value !== 0)
                    知能：{{ $result->intelligence() }}
                @endif
                @if($result->ability->sport->value !== 0)
                    運動：{{ $result->sport() }}
                @endif
                <br/>
                @if($result->ability->visual->value !== 0)
                    容姿：{{ $result->visual() }}
                @endif
                @if($result->health->value !== 0)
                    健康：{{ $result->health() }}
                @endif
                @if($result->ability->sense->value !== 0)
                    感性：{{ $result->sense() }}
                @endif
                <br/>
                @if($result->evaluation->business->value !== 0)
                    仕事: {{ $result->business() }}
                @endif
                @if($result->evaluation->happiness->value !== 0)
                    幸福: {{ $result->happiness() }}
                @endif
                </p>
            </div>
        </div>

    </body>


    <!-- <div class="bottom-panel">
        {{ $result->message }}
    </div> -->
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
            const buttons = document.querySelectorAll('.button');
            const se = new Audio('{{ asset('sounds/se/decide-button-a.mp3') }}');
            se.volume = 0.3;
            buttons.forEach(button => {
                button.addEventListener('click', function () {
                    se.currentTime = 0;
                    se.play();
                });
            });

            // BGM の設定
            @if($result->success)
            setupBgm('{{ asset('sounds/positive/breakthrough-moment_v2.mp3') }}');
            @else
            setupBgm('{{ asset('sounds/negative/bleeding-my-heart_v2.mp3') }}');
            @endif

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
