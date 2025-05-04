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
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .panel {
            background: rgba(255, 255, 255, 0.4);
            width: 100%;
            height: 415px;
            top: calc(50% - 295px);
            padding: 10px;
            position: absolute;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 36px;
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

        .event-text {
            width: 700px;
            height: 300px;
            padding: 15px 25px;
            color: #070606;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            max-width: 80%;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* .bottom-panel {
            width: 1124px;
            height: 80px;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            padding: 20px 0;
        } */

        .button {
            width: 1124px;
            height: 80px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 15px 60px;
            font-size: 22px;
            border: none;
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
    <div class="header">
      <div class="title">
        人生やり直しゲーム
        <button id="bgm-toggle" style="background: none; border: none; margin-left: 10px; cursor: pointer;">
            <img id="bgm-icon" src="{{ asset('icon/gray_off.png') }}" alt="BGMアイコン" width="24" height="24">
        </button>
    </div>
      <div class="turn">西暦：{{ $player->currentYear() }}</div>
    </div>
    <div class="event-area"></div>
    <div class="panel">
        <div class="event-text">
            - {{ $result->result() }} -<br>
            @if($result->totalMoney->value !== 0)
                資産：{{ $result->money() }}<br>
            @endif
            @if($result->income->value !== 0)
                年収：{{ $result->income() }}<br>
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
            @if(!empty($result->message))
                <br><br>
                {{ $result->message }}
            @endif
        </div>
    </div>

    <!-- <div class="bottom-panel">
        {{ $result->message }}
    </div> -->

    @if($result->dead)
        <button id="button-happy" style="margin-top:20px;" class="button" onclick="location.href='{{ route('result', ['id' => $player->id]) }}'">おわり</button>
    @else
        <button id="button-home" style="margin-top:20px;" class="button" onclick="location.href='{{ route('home', ['id' => $player->id]) }}'">ホーム</button>
    @endif
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
        };

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
        });
    </script>
@endpush
