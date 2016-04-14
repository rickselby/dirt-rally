@extends('page')

@section('header')
    <div class="page-header">
        <h1>{{ $system->name }} Standings</h1>
    </div>
@endsection

@section('content')

    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>Pos.</th>
            <th>Driver</th>
            @foreach($seasons AS $season)
                <th data-sortInitialOrder="desc">
                    <a href="{{ route('standings.season', [$system->id, $season->id]) }}">
                        {{ $season->name }}
                    </a>
                </th>
            @endforeach
            <th data-sortInitialOrder="desc">Total Points</th>
        </tr>
        </thead>
        <tbody>
        @foreach($points AS $position => $detail)
            <tr>
                <th>{{ $detail['position'] }}</th>
                <th>{{ $detail['driver']->name }}</th>
                @foreach($seasons AS $season)
                    <td>{{ $detail['points'][$season->id] or '' }}</td>
                @endforeach
                <td>{{ $detail['total'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @include('tablesorter')

@endsection