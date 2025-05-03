@extends('layouts.parent')

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
