@extends('layouts.parent')

@push('styles')
    @vite('resources/css/end.css')
    <style>
        .image{
            background: url('{{ route('background', ['id' => $player->backgroundId ?? 0]) }}') no-repeat center center;
        }

        .character-image{
            background: white url('{{ route('face', ['id' => $player->playerFaceId ?? 0]) }}') no-repeat center center;
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
                        {{ $player->name->value }}　　{{ $player->sexName->value }}性　　{{ $player->turn->value }}歳<br/>
                        資産：{{ $player->totalMoney->format() }}<br/>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/end.js')
@endpush
