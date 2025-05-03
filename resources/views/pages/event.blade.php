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
            padding: 10px;
            position: absolute;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 36px;
        }

        .event-area {
            width: 100%;
            max-width: 1280px;
            aspect-ratio: 16/9;
            background: url('生成したイベント画像を配置する箇所') no-repeat center center;
            background-size: cover;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 40px;
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .event-text {
            padding: 15px 25px;
            color: #070606;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            max-width: 80%;
        }

        .bottom-panel {
            width: 100%;
            max-width: 1280px;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            padding: 20px 0;
        }

        .button {
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
    <div class="event-area"></div>
    <div class="panel">
        <div class="event-text">
            - {{ $result->result() }} -<br>
            @if($result->totalMoney->value !== 0)
                資産：{{ $result->money() }}<br>
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
            <br/>
            @if($result->evaluation->business->value !== 0)
                仕事: {{ $result->business() }}
            @endif
            @if($result->evaluation->love->value !== 0)
                恋愛: {{ $result->love() }}
            @endif
        </div>
    </div>

    <div class="bottom-panel">
        {{ $result->message }}
    </div>

    <button style="margin-top:20px;" class="button" onclick="location.href='{{ route('home', ['id' => $player->id]) }}'">ホーム</button>
@endsection
