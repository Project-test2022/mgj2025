@extends('layouts.parent')

@push('styles')
    <style>
        .hidden{
            display: none !important;
        }

        html,body{
            letter-spacing: 0.1em;
        }

        .stacking-toggle_contents_start{
            /* background-color:white; */
            position:absolute;

            display: flex;
            flex-direction: column;
            justify-content: space-between;

            width: 600px;
            height: 400px;

            margin: 0;
        }

        .toggle{
            /* background-color:rgb(255, 164, 164); */

            display: flex;
            align-items: center;          /* 垂直方向（交差軸）中央 */
            flex: 0 0 auto; 
            margin-bottom:3px;

            font-size: 0.8em;
            letter-spacing: 0.1em;

            gap: 0.6em;

            transform: translate(0, 0em);
            width: 100%;
        }

        .toggle-button {
            display: inline-block;
            position: relative;
            width: 2.5rem;
            height: 1.2rem;
            border-radius: 50px;
            /* border: 3px solid #dddddd; */
            box-sizing: content-box;
            cursor: pointer;
            transition: border-color .4s;
            background-color:rgb(67, 67, 67);
        }

        .toggle-button:has(:checked) {
            background-color: #c12d33;
        }

        .toggle-button::after {
            position: absolute;
            top: 50%;
            left: 5px;
            transform: translateY(-50%);
            width: 0.9rem;
            height: 0.9rem;
            border-radius: 50%;
            background-color: #c8c8c8;
            content: '';
            transition: left .4s;
        }

        .toggle-button:has(:checked)::after {
            left: 1.4rem;
            background-color: white;
        }

        .toggle-button input {
            display: none;
        }

        .start-contents{
            flex: 1 1 auto; 
            border: solid 1px white;
            width: 100%;

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;

            gap: 0.5rem;

        }

        .top-title{

            /* background-color:rgb(164, 220, 255); */

            font-size:2.2em;
            letter-spacing: 0.3em;
            text-align: center;
            
            width: 100%;
            height:1.5em;
        }
        .top-text{
            /* background-color:rgb(199, 255, 164); */

            font-size:0.8em;
            letter-spacing: 0.2em;
            text-align: center;

            width: 100%;
            height:1.5em;
        }
        
        .top-form{
            display: flex; 
            width: 70%;
            margin-top: 0.5em;
            /* gap:0.5em; */
        }

        .label{
            align-items: center;          /* 垂直方向（交差軸）中央 */
            text-align: justify;
            text-align-last: justify;
            letter-spacing: 0;
            width: 30%;
            height:1.5em;
        }

        .input-box{

            display: flex;
            justify-content: center;     /* 横方向中央 */
            align-items: center;         /* 縦方向中央 */

            width: 100%;
            height:1.5em;

        }

        input,select{
            text-align: center;
            letter-spacing: 0.2em;
            border: none;
            width: 100%;
            height:1.5em;
        }
        
        .ran-sel{
            display: flex;
            justify-content: center;     /* 横方向中央 */
            align-items: center;         /* 縦方向中央 */
            width: 35%;
            height:1.5em;
        }

        label{
            display: flex;
            justify-content: center;     /* 横方向中央 */
            align-items: center;         /* 縦方向中央 */
        }

        .start-button{
            flex: 0 0 auto; 

            display: inline-block;
            margin-top: -1px;
            width: 100%;

            color: #333;
            font-size:1.05em;
            
            border: solid 1px white;
        }

        .start-button button{
            height: 2em;

            background-color: rgba(255, 255, 255, 0.4);
            border: none;

            letter-spacing: 0.5em;
            cursor: pointer;
        }

        .start-button button:hover{
            background-color: rgba(255, 255, 255, 0.6);
        }


    </style>
@endpush

@section('content')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <head>
    </head>
    <body>

        <!-- トグル-コンテンツ-startボタン縦積みdiv -->
        <form action="{{ route('start') }}" method="POST">
            <div class="stacking-toggle_contents_start">
                <!-- トグルボタン -->
                <div class="toggle">
                    <img id="bgm-icon" src="{{ asset('icon/gray_off.png') }}" alt="BGMアイコン" width="24" height="24">
                    <label class="toggle-button">
                        <input id="bgm-toggle" type="checkbox"/>
                    </label>
                    <span id="bgm-message">音ありでゲームを楽しみますか？</span>
                </div>
                <div class="start-contents">
                    <div class="top-title">
                        人生やり直しゲーム
                    </div>
                    <div class="top-text">
                        次に生まれる人生を選択・入力してください。
                    </div>
                    <!-- 名前入力欄 -->
                    <div class="top-form">
                        <div class="label">
                            名前
                        </div>
                        <div class="tmp">
                            ：
                        </div>
                        <div class="input-box">
                            <input type="text" name="name" value="{{ old('name') }}">
                        </div>
                        <div class="ran-sel">
                            <input type="checkbox" name="name_random" id="toggle1" class="toggle-checkbox hidden">
                            <label for="toggle1" class="checkbox-label">
                                <img src="{{ asset('images/select.png') }}" alt="OFF" id="toggle1-img">
                            </label>
                        </div>
                    </div>
                    <!-- 生年入力欄 -->
                    <div class="top-form">
                        <div class="label">
                            生まれ年
                        </div>
                        <div class="tmp">
                            ：
                        </div>
                        <div class="input-box">
                            <input type="number" name="birth_year" class="form-input" value="{{ old('birth_year', 2000) }}">
                        </div>
                        <div class="ran-sel">
                            <input type="checkbox" name="birth_year_random" id="toggle2" class="toggle-checkbox hidden">
                            <label for="toggle2">
                                <img src="{{ asset('images/select.png') }}" alt="OFF" id="toggle2-img">
                            </label>
                        </div>
                    </div>
                    <!-- 性別入力欄 -->
                    <div class="top-form">
                        <div class="label">
                            性別
                        </div>
                        <div class="tmp">
                            ：
                        </div>
                        <div class="input-box">
                            <select name="gender" class="form-input">
                                <option value disabled selected>--選択してください--</option>
                                @foreach($sexes as $sex)
                                    <option
                                        value="{{ $sex->sex_cd }}" @selected(old('gender') == $sex->sex_cd)>{{ $sex->sex_nm }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="ran-sel">
                            <input type="checkbox" name="gender_random" id="toggle3" class="toggle-checkbox hidden">
                            <label for="toggle3" class="checkbox-label">
                                <img src="{{ asset('images/select.png') }}" alt="OFF" id="toggle3-img">
                            </label>
                        </div>
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
    </body>
@endsection

@push('scripts')
    <script src="{{ asset('js/title.js?v='.config('app.version')) }}"></script>
    <script src="{{ asset('js/bgm.js') }}"></script>
    <script>
        function updateBgmIcon() {
            const icon = document.getElementById('bgm-icon');
            const enabled = localStorage.getItem('bgm_enabled') === 'true';
            icon.src = enabled
                ? '{{ asset('icon/red_on.png') }}'
                : '{{ asset('icon/gray_off.png') }}';
        }

        document.addEventListener('DOMContentLoaded', function () {

            // 音
            const bgmToggle = document.getElementById('bgm-toggle');
            const se = new Audio('{{ asset('sounds/se/decide-button-a.mp3') }}');
            se.volume = 0.3;

            const message = document.getElementById('bgm-message');
            setupBgm('{{ asset('sounds/choice/the-decision.mp3') }}');

            bgmToggle.addEventListener('click', () => {
                se.currentTime = 0;
                se.play();

                toggleBgm();
                updateBgmIcon();
                if (bgmToggle.checked) {
                    bgm.play().catch(err => console.log('BGM再生失敗:', err));
                    message.innerHTML = "音ありでゲームを楽しみます。";
                    message.style.color = '#c12d33';
                } else {
                    message.innerHTML = "音ありでゲームを楽しみますか？";
                    message.style.color = 'gray';
                }
            });

            // const checkbox = document.getElementById('bgm-toggle-checkbox');
            // checkbox.addEventListener('change', () => {
            //     toggleBgm();
            //     updateBgmIcon();
            //     if (checkbox.checked) {
            //         bgm.play().catch(err => console.log('BGM再生失敗:', err));
            //         message.innerHTML = "音ありでゲームを楽しみます";
            //         message.style.color = '#c12d33';
            //     } else {
            //         message.innerHTML = "音ありでゲームを楽しみますか？";
            //         message.style.color = 'gray';
            //     }
            // });

            const enabled = localStorage.getItem('bgm_enabled') === 'true';
            if (enabled) {
                bgm.play().catch(err => console.log('BGM再生失敗:', err));
                message.innerHTML = "音ありでゲームを楽しみます。";
                message.style.color = 'red';
            } else {
                message.innerHTML = "音ありでゲームを楽しみますか？";
                message.style.color = 'rgb(75, 75, 75)';
            }
        });

        // random / select　画像
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.toggle-checkbox').forEach(checkbox => {
                // チェックボックスの直近コンテナ内からラベルと img を取得
                const container = checkbox.closest('.ran-sel');
                const label     = container.querySelector('label');
                const img       = label.querySelector('img');

                // アイコン更新関数
                function updateIcon() {
                if (checkbox.checked) {
                    img.src = '{{ asset("images/random.png") }}';
                    img.alt = 'ON';
                } else {
                    img.src = '{{ asset("images/select.png") }}';
                    img.alt = 'OFF';
                }
                }


                // 変更時に切り替え
                checkbox.addEventListener('change', updateIcon);

                // ページ読み込み時にも初期状態を反映
                updateIcon();
            });
        });

    </script>
@endpush
