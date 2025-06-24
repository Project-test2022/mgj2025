@extends('layouts.parent')

@pushonce('styles')
    @vite('resources/css/home.css')
    <style>
        .image{
            background: url('{{ route('background', ['id' => $player->backgroundId ?? 0]) }}') no-repeat center center;
        }
        .character-image{
            background: white url('{{ route('face', ['id' => $player->playerFaceId ?? 0]) }}') no-repeat center center;
        }
    </style>
@endpushonce

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
                    <div id="profile-or-ability" class="toggle-element">プロフィール</div>
                </div>
                <div id="main-profile" class="profile-info">
                    <p>
                        {{ $player->name }}({{ $player->turn->value }})　　{{ $player->sexName }}性　　職業: {{ $player->job?->value }}<br/>
                        資産：{{ $player->totalMoney->format() }}　　年収：{{ $player->income->format() }}<br/>
                        パートナー：【工事中】
                    </p>
                    <p>
                        仕事：{{ $player->evaluation->business }}　　幸福：{{ $player->evaluation->happiness }}　　健康：{{ $player->health }}<br/>
                        知能：{{ $player->ability->intelligence }}　　運動：{{ $player->ability->sport }}　　容姿：{{ $player->ability->visual }}　　感性：{{ $player->ability->sense }}
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
@endsection

@push('scripts')
    @vite('resources/js/home.js')
@endpush
