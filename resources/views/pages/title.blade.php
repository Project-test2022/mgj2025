@extends('layouts.parent')

@section('content')
    <h1>Title Page</h1>
    <p>This is the title page of the application.</p>
    <form action="{{ route('start') }}" method="POST">
        @csrf
        <button type="submit">Start Game</button>
    </form>
@endsection
