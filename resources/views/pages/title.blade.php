@extends('layouts.parent')

@section('content')
    <h1>Title Page</h1>
    <p>This is the title page of the application.</p>
    <button onclick="location.href='{{ route('home') }}'">Start Game</button>
@endsection
