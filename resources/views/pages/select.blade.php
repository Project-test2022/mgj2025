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

        .event-area {
            width: 90%;
            max-width: 1000px;
            aspect-ratio: 16/9;
            background: url('{{ asset('images/crossroadsinlife.png?v='.config('app.version')) }}') no-repeat center center;
            background-size: cover;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 40px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(2px);
        }

        .event-text {
            background: rgba(248, 246, 246, 0.4);
            display: inline-block;
            padding: 15px 25px;
            color: #070606;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            max-width: 80%;
            line-height: 1.8;
        }

        .buttons {
            display: flex;
            gap: 80px;
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
    <div class="event-area">
        <div class="event-text">
            {{ $event->content }}
        </div>
    </div>

    <form action="{{ route('event.select', ['id' => $player->id]) }}" method="POST">
        @csrf
        <input type="hidden" name="situation" value="{{ $situation->value }}">
        <input type="hidden" name="event" value="{{ $event->content }}">
        <input type="hidden" name="choice1" value="{{ $event->choice1->content }}">
        <input type="hidden" name="rate1" value="{{ $event->choice1->rate }}">
        <input type="hidden" name="choice2" value="{{ $event->choice2->content }}">
        <input type="hidden" name="rate2" value="{{ $event->choice2->rate }}">
        <div class="buttons">
            <button type="submit" name="ok" class="button">{{ $event->choice1->content }}</button>
            <button type="submit" name="ng" class="button">{{ $event->choice2->content }}</button>
        </div>
    </form>
@endsection
