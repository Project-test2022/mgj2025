@extends('layouts.parent')

@push('styles')
    @vite('resources/css/event.css')
    <style>
        .image{
            background: url('{{ $result->success ? asset('images/choice_success.jpg') : asset('images/choice_lose.jpg') }}') no-repeat center center;
        }
    </style>
@endpush

@section('content')
    <!-- プロフィールエリア -->
    <div class="information">
        <!-- 縦詰み要素のためのdiv -->
        <div class="stacking-image-header">
            <!-- ヘッダー -->
            <x-layouts.header :$player />

            <!-- 家の背景 -->
            <div class="image"></div>

            <!-- ボタン -->
            @if($result->dead)
                <button
                    id="button-happy"
                    class="event-button"
                    onclick="location.href='{{ route('result', ['id' => $player->id]) }}'"
                >
                    おわり
                </button>
            @else
                <button
                    class="event-button"
                    onclick="location.href='{{ route('home', ['id' => $player->id]) }}'"
                >
                    <img src="{{ asset('icon/let_go_home.png') }}" alt="home"/>
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
                    資産：{{ $result->money() }}<br/>
                @endif
                @if($newJob)
                    職業：{{ $newJob }} New!
                @endif
                @if($incomeDiff)
                    年収：{{ $incomeDiff->format(true) }}<br/>
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

    <input type="hidden" id="result" value="{{ $result->success ? 'true' : 'false' }}">
@endsection

@push('scripts')
    @vite('resources/js/event.js')
@endpush
