@extends('layouts.parent')

@push('styles')
    @vite('resources/css/select.css')
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
@endsection

@push('scripts')
    @vite('resources/js/select.js')
@endpush
