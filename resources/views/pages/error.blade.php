@extends('layouts.parent')

@push('styles')
    @vite('resources/css/error.css')
@endpush

@section('content')
    <div class="container">
        <div class="title" style="color: red">エラーが発生しました</div>
        <button class="start-button" onclick="location.href='{{ route('home', ['id' => $id]) }}'">ホーム</button>
    </div>
@endsection
