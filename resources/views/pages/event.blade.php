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
      justify-content: center;
      align-items: center;
    }

    .event-area {
      width: 90%;
      max-width: 1000px;
      aspect-ratio: 16/9;
      background: url('生成したイベント画像を配置する箇所') no-repeat center center;
      background-size: cover;
      position: relative;
      display: flex;
      justify-content: center;
      align-items: center;
      margin-bottom: 40px;
      border: 1px solid rgba(255,255,255,0.4);
    }

    .event-text {
      background: hsla(0, 100%, 100%, 0.3);
      max-width: 1000px;
      width: 95%;
      padding: 20px;
      color: rgb(7, 7, 7);
      font-size: 22px;
      font-weight: bold;
      text-align: center;
      position: absolute;
      top: 40%;
      left: 50%;
      transform: translate(-50%, -50%);
      letter-spacing: 2px;
    }

    .bottom-panel {
      width: 90%;
      max-width: 1000px;
      background: rgba(0, 0, 0, 0.5);
      color: white;
      text-align: center;
      font-size: 22px;
      font-weight: bold;
      padding: 20px 0;
    }
</style>
@endpush

@section('content')
<div class="event-area">
    <div class="event-text">
      - SUCCESS -<br>
      知能：-10　資産：¥100,000
    </div>
  </div>

  <div class="bottom-panel">
    仕事がバズった！成功だ！
</div>
@endsection
