
<table class="table sortable table-condensed">
    <thead>
    <tr>
        <th>Pos.</th>
        <th>Driver</th>
        @foreach($event->sessions AS $session)
            @if (\RacesSession::hasPoints($session))
                <th>{{ $session->name }}</th>
            @endif
        @endforeach
        <th>Total Points</th>
    </tr>
    </thead>
    <tbody>
    @foreach(\Positions::addEquals(\RacesDriverStandings::eventSummary($event)) AS $detail)
        <tr>
            <th>{{ $detail['position'] }}</th>
            <th>
                @include('races.driver.name', ['entrant' => $detail['entrant']])
            </th>
            @foreach($event->sessions AS $session)
                @if (\RacesSession::hasPoints($session))
                    @if (isset($detail['points'][$session->id]))
                        <td class="position {{ \Positions::colour($detail['positions'][$session->id], $detail['points'][$session->id]) }}"
                            data-points="{{ $detail['points'][$session->id] }}"
                            data-position="{{ $detail['positions'][$session->id] }}">
                            {{ $detail['positions'][$session->id] }}
                        </td>
                    @else
                        <td></td>
                    @endif
                @endif
            @endforeach
            <td class="points">{{ $detail['totalPoints'] }}</td>
        </tr>
    @endforeach
    </tbody>

</table>