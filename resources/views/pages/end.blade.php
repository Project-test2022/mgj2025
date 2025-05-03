@extends('layouts.parent')

@push('styles')
<style>
    html, body {
      margin: 0;
      padding: 0;
      width: 100%;
      height: 100%;
      font-family: 'Arial', sans-serif;
      background: url('backimage.png') no-repeat center center;
      background-size: cover;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .header {
      font-size: 24px;
      color: #333;
      letter-spacing: 5px;
      margin-bottom: 20px;
      align-self: flex-start;
    }

    .main-wrapper {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 90%;
      max-width: 1000px;
    }

    .main-area {
      position: relative;
      width: 1124px;
      height: 505px;
      background: url('home.png') no-repeat center center;
      background-size: cover;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .panel {
      position: absolute;
      top: calc(50% - 285px); /* ←画像の中央よりちょい上に設定（微調整できる） */
      left: 50%;
      transform: translateX(-50%);
      width: 100%;
      height: 415px;
      background: rgba(255, 255, 255, 0.4);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 36px;
      padding: 20px;
      z-index: 1;
    }

    .character-frame {
      position: relative;
      width: 240px;
      height: 300px;
      background: url('image.png') no-repeat center center;
      background-size: cover;
    }

    .character-frame img {
      position: absolute;
      top: 8%;
      left: 16%;
      width: 66%;
      height: 83%;
      object-fit: cover;
    }

    .profile-info {
      width: 700px;
      height: 255px;
      background: rgba(0, 0, 0, 0.7);
      color: white;
      font-size: 22px;
      text-align: left;
      padding: 20px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      mix-blend-mode: multiply;
    }

    .question {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .buttons {
      display: flex;
      gap: 80px;
      margin-top: 40px;
    }

    .button {
      background: rgba(0, 0, 0, 0.7);
      color: white;
      width: 360px;
      height: 80px;
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
<div class="header">人生やり直しゲーム</div>

<div class="main-wrapper">
  <div class="main-area"></div>
  <div class="panel">
      <div class="character-frame">
          <img src="crossroadsinlife_.png" alt="人物写真">
      </div>
      <div class="profile-info">
          <div class="question">「この人生は幸せでしたか？」</div>
              永田　智　男性　36歳<br>
              資産：¥100,000,000
          </div>
      </div>

  <div class="buttons">
      <button class="button">幸せでした</button>
      <button class="button">もう一回</button>
  </div>
</div>
@endsection
