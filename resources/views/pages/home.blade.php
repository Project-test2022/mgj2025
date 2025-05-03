@extends('layouts.parent')

@pushonce('styles')
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: sans-serif;
            background: url('background.jpg') center/cover no-repeat;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }
        .container {
            display: flex;
            background: rgba(0, 0, 0, 0.6);
            border-radius: 8px;
            overflow: hidden;
            max-width: 800px;
            width: 100%;
        }
        .left-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: #111;
            position: relative;
        }
        .title-box {
            text-align: center;
        }
        .title-box h2 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        .title-box p {
            font-size: 1rem;
            color: #ccc;
        }
        .refresh-icon {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 32px;
            height: 32px;
            background: url('refresh.svg') center/contain no-repeat;
        }
        .right-panel {
            flex: 1;
            background: #fff;
            color: #000;
            padding: 30px;
        }
        .right-panel h3 {
            margin-bottom: 20px;
        }
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .form-group label {
            width: 60px;
        }
        .form-group input[type="text"],
        .form-group select {
            flex: 1;
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
        }
        .form-group input[type="checkbox"] {
            transform: scale(1.2);
        }
        .buttons {
            margin-top: 25px;
            display: flex;
            gap: 10px;
        }
        .buttons button {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
        }
        .btn-random { background: #f0ad4e; color: #fff; }
        .btn-start  { background: #5cb85c; color: #fff; }
    </style>
@endpushonce

@section('content')
    <div class="container">
        <div class="left-panel">
            <div class="title-box">
                <h2>人生やり直しゲーム</h2>
                <p>セーブ機能はありません</p>
            </div>
            <div class="refresh-icon"></div>
        </div>
        <div class="right-panel">
            <h3>基本情報を入力</h3>
            <form>
                <div class="form-group">
                    <label for="name">名前</label>
                    <input type="text" id="name" name="name" placeholder="名前を入力">
                    <input type="checkbox" id="rand-name" name="rand-name">
                    <label for="rand-name">ランダム</label>
                </div>
                <div class="form-group">
                    <label for="birth-year">生年</label>
                    <input type="text" id="birth-year" name="birth-year" placeholder="YYYY">
                    <input type="checkbox" id="rand-year" name="rand-year">
                    <label for="rand-year">ランダム</label>
                </div>
                <div class="form-group">
                    <label for="gender">性別</label>
                    <input type="text" id="gender" name="gender" placeholder="男/女">
                    <input type="checkbox" id="rand-gender" name="rand-gender">
                    <label for="rand-gender">ランダム</label>
                </div>
                <div class="form-group">
                    <label for="difficulty">難易度</label>
                    <select id="difficulty" name="difficulty">
                        <option value="normal">ノーマル</option>
                        <option value="hard" selected>ハードモード</option>
                        <option value="easy">イージー</option>
                    </select>
                </div>
                <div class="buttons">
                    <button type="button" class="btn-random">親ガチャを回す</button>
                    <button type="submit" class="btn-start">スタート</button>
                </div>
            </form>
        </div>
    </div>
@endsection
