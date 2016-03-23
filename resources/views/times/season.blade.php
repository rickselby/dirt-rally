@extends('page')

@section('header')
    <div class="page-header">
        <h1>
            <a href="{{ route('times.index') }}">Total Time</a>:
            {{ $season->name }}
        </h1>
    </div>
@endsection

@section('content')

    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>Pos.</th>
            <th>Driver</th>
            @foreach($season->events AS $event)
                <th>
                    <a href="{{ route('times.event', [$season->id, $event->id]) }}">
                        {{ $event->name }}
                    </a>
                </th>
            @endforeach
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($times['times'] AS $position => $detail)
            <tr>
                <th>{{ $position + 1 }}</th>
                <th>{{ $detail['driver']->name }}</th>
                @foreach($season->events AS $event)
                    <td class="{{ $detail['dnss'][$event->id] ? 'danger' : ($detail['dnfs'][$event->id] ? 'warning' : '') }}">
                        {{ StageTime::toString($detail['events'][$event->id]) }}
                    </td>
                @endforeach
                <td>{{ StageTime::toString($detail['total']) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @include('times.legend')

@endsection