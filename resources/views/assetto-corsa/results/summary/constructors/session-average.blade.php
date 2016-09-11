
<table class="table sortable table-condensed">
    <thead>
    <tr>
        <th>Pos.</th>
        <th>Driver</th>
        @foreach($event->sessions AS $session)
            @if (\ACSession::hasPoints($session))
                <th>{{ $session->name }}</th>
            @endif
        @endforeach
        <th>Total Points</th>
    </tr>
    </thead>
    <tbody>
    @foreach(\Positions::addEquals(\ACConstructorStandings::eventSummary($event)) AS $detail)
        <tr>
            <th>{{ $detail['position'] }}</th>
            <th>
                {{ $detail['car']->full_name }}
            </th>
            @foreach($event->sessions AS $session)
                @if (\ACSession::hasPoints($session))
                    @if (isset($detail['pointsList'][$session->id]))
                        <td class="position {{ \Positions::colour($detail['positions'][$session->id], $detail['pointsList'][$session->id]) }}"
                            data-points="{{ $detail['pointsList'][$session->id] }}"
                            data-position="{{ $detail['positions'][$session->id] }}">
                            {{ $detail['positions'][$session->id] }}
                        </td>
                    @else
                        <td></td>
                    @endif
                @endif
            @endforeach
            <td class="points">{{ round($detail['points'], 2) }}</td>
        </tr>
    @endforeach
    </tbody>

</table>