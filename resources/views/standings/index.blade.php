@extends('page')

@section('header')
    <ol class="breadcrumb">
        <li class="active">Standings</li>
    </ol>
@endsection

@section('content')

    <ul>
        @foreach($systems AS $system)
            <li>
                <a href="{{ route('dirt-rally.standings.system', $system) }}" class="tablesorter-noSort">
                    {{ $system->name }}
                </a>
            </li>
        @endforeach
    </ul>

@endsection