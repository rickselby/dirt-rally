@extends('page')

@section('content')

    @if ($event->importing)
        @include('dirt-rally.import-in-progress')
    @elseif(!$event->isComplete())
        @include('dirt-rally.event-not-complete')
    @else

        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Pos.</th>
                <th>Nation</th>
                <th data-sortInitialOrder="desc">Total Points</th>
                <th>Drivers</th>
                <th data-sortInitialOrder="desc">Average Points</th>
            </tr>
            </thead>
            <tbody>
            @foreach($points AS $position => $detail)
            <tr>
                <th>{{ $position + 1 }}</th>
                <th>
                    <img src="{{ route('nation.image', $detail['entity']) }}" alt="{{ $detail['entity']->name }}" />
                    {{ $detail['entity']->name }}
                </th>
                <td>{{ $detail['total']['sum'] }}</td>
                <td>{{ count($detail['points']) }}</td>
                <td>{{ round($detail['total']['points'], 2) }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>

        @include('tablesorter')

    @endif {{-- importing test --}}

@endsection