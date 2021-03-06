@extends('page')

@section('content')

    <p>
        <a href="{{ route('dirt-rally.nationstandings.overview', $championship) }}"
           class="btn btn-primary" role="button">
            View all points on one page
        </a>
    </p>

    <table class="table sortable table-bordered table-hover">
        <thead>
        <tr>
            <th>Pos.</th>
            <th>Nation</th>
            @foreach($championship->getOrderedSeasons() AS $season)
                <th data-sortInitialOrder="desc">
                    <a href="{{ route('dirt-rally.nationstandings.season', [$championship, $season]) }}" class="tablesorter-noSort">
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
                <th>
                    @include('nation.image', ['nation' => $detail['entity']])
                    {{ $detail['entity']->name }}
                </th>
                @foreach($championship->getOrderedSeasons() AS $season)
                    <td class="points {{ \Positions::colour(isset($detail['positions'][$season->id]) ? $detail['positions'][$season->id] : null) }}">
                        @if ($season->isComplete())
                            {{ isset($detail['points'][$season->id]) ? round($detail['points'][$season->id], 2) : '' }}
                        @else
                            <em class="text-muted">
                                {{ isset($detail['points'][$season->id]) ? round($detail['points'][$season->id], 2) : '' }}
                            </em>
                        @endif
                    </td>
                @endforeach
                <td class="points">{{ round($detail['total'], 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
