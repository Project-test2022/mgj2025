@extends('layouts.parent')

@pushOnce('styles')
    @vite('resources/css/title.css')
@endPushOnce

@section('content')
    <!-- トグル-コンテンツ-startボタン縦積みdiv -->
    <form action="{{ route('start') }}" method="POST">
        @csrf
        <div class="stacking-toggle-contents-start">
            <!-- トグルボタン -->
            <div class="toggle">
                <img id="bgm-icon" src="{{ asset('icon/gray_off.png') }}" alt="BGMアイコン" width="24" height="24">
                <label class="toggle-button">
                    <input id="bgm-toggle" type="checkbox"/>
                </label>
                <span id="bgm-message">音ありでゲームを楽しみますか？</span>
            </div>
            <div class="start-contents">
                <div class="top-title">人生やり直しゲーム</div>
                <div class="top-text">次に生まれる人生を選択・入力してください。</div>
                <!-- 名前入力欄 -->
                <div class="top-form">
                    <div class="label">名前</div>
                    <div>：</div>
                    <div class="input-box">
                        <input type="text" name="name" value="{{ old('name') }}">
                    </div>
                    <x-select-toggle name="name_random" />
                </div>
                <!-- 生年入力欄 -->
                <div class="top-form">
                    <div class="label">生まれ年</div>
                    <div>：</div>
                    <div class="input-box">
                        <input type="number" name="birth_year" class="form-input" value="{{ old('birth_year', 2000) }}">
                    </div>
                    <x-select-toggle name="birth_year_random" />
                </div>
                <!-- 性別入力欄 -->
                <div class="top-form">
                    <div class="label">性別</div>
                    <div>：</div>
                    <div class="input-box">
                        <select name="gender" class="form-input">
                            <option value disabled selected>--選択してください--</option>
                            @foreach($sexes as $sex)
                                <option
                                    value="{{ $sex->sex_cd }}"
                                    @selected(old('gender') == $sex->sex_cd)
                                >
                                    {{ $sex->sex_nm }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <x-select-toggle name="gender_random" />
                </div>
            </div>
            <div class="start-button">
                <button id="btn" type="submit" class="start-button">START</button>
            </div>
            @if($errors->any())
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            @endif
        </div>
    </form>
@endsection

@pushOnce('scripts')
    @vite('resources/js/title.js')
@endPushOnce
