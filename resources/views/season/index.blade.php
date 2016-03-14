@extends('page')

@section('header')
    <div class="page-header">
        <h1>Seasons</h1>
    </div>
@endsection

@section('content')

    <ul>
        @foreach($seasons as $season)
            <li>
                <a href="{{ route('season.show', ['id' => $season->id]) }}">
                    {{ $season->name }}
                </a>
            </li>
        @endforeach
    </ul>

    @if (Auth::guest())
    <p>
        <a class="btn btn-social btn-google" href="<?=route('login.google') ?>">
            <span class="fa fa-google"></span> Sign in with Google
        </a>
    </p>
    @endif

    @if (Auth::user() && Auth::user()->admin)
    <a class="btn btn-small btn-info" href="{{ route('season.create') }}">Add a new season</a>
    @endif

@endsection