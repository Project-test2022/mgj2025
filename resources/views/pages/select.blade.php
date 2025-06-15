@extends('layouts.parent')

@push('styles')
    <style>
        .image{
            background: url('{{ asset('images/crossroadsinlife.png?v='.config('app.version')) }}') no-repeat center center;
        }
        
        .event-text{
            text-align: center;
            color: rgba(35, 35, 35);
        }

        .contents{
            justify-content: center;
            align-items: center;
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
                <form action="{{ route('event.select', ['id' => $player->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="{{ $action }}">
                    <input type="hidden" name="event" value="{{ $event->content }}">
                    <input type="hidden" name="choice1" value="{{ $event->choice1->content }}">
                    <input type="hidden" name="rate1" value="{{ $event->choice1->rate }}">
                    <input type="hidden" name="choice2" value="{{ $event->choice2->content }}">
                    <input type="hidden" name="rate2" value="{{ $event->choice2->rate }}">
                    <div class="buttons">
                        <button type="submit" name="ok" class="button">{{ $event->choice1->content }}
                            (成功率 {{ $event->choice1->rate }}%)
                        </button>
                        <button type="submit" name="ng" class="button">{{ $event->choice2->content }}
                            (成功率 {{ $event->choice2->rate }}%)
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- 背景の白い帯 -->
            <div class="white-panel"></div>

            <!-- イベントテキストエリア -->
            <div class ="contents">
                <!-- イベントテキスト -->
                <div class="event-text">
                    <!-- <p>イベントテキスト</p> -->
                    {{ $event->content }}
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
                btn.addEventListener('click', function () {
                    se.currentTime = 0;
                    se.play();
                });
            });

            // BGM の設定
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
