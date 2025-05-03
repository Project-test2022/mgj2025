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
      background: url('crossroadsinlife.png') no-repeat center center;
      background-size: cover;
      position: relative;
      display: flex;
      justify-content: center;
      align-items: center;
      margin-bottom: 40px;
      border: 1px solid rgba(255,255,255,0.4);
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
      カービィに扮装する仕事を依頼された・・<br>
      引き受けますか？
    </div>
  </div>

  <div class="buttons">
    <button class="button">OK</button>
    <button class="button">NG</button>
</div>
@endsection
