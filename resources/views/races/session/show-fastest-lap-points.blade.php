<div class="panel {{ \RacesSession::hasFastestLapPoints($session) ? 'panel-success' : 'panel-warning' }}">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a role="button" data-toggle="collapse" href="#fastest-lap-points">
                Fastest Lap Points
            </a> <span class="caret"></span>
        </h3>
    </div>
    <div class="panel-collapse collapse" id="fastest-lap-points" role="tabpanel">
        <div class="panel-body">
            @if (count($session->entrants))
                {!! Form::open(['route' => ['races.championship.event.session.entrants.fastest-lap-points-sequence', $session->event->championship, $session->event, $session], 'class' => 'form-horizontal']) !!}
                <div class="form-group">
                    {!! Form::label('sequence', 'Assign Points Sequence', ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-9">
                        {!! Form::select('sequence', $sequences, null, ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9">
                        {!! Form::submit('Assign Fastest Lap Points', ['class' => 'btn btn-primary']) !!}
                    </div>
                </div>
                {!! Form::close() !!}

                {!! Form::open(['route' => ['races.championship.event.session.entrants.fastest-lap-points', $session->event->championship, $session->event, $session], 'class' => 'form-horizontal']) !!}
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Pos.</th>
                        <th>Driver</th>
                        <th>Points</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($session->entrants()->orderBy('position')->get() AS $entrant)
                        <tr>
                            <th>{{ $entrant->position }}</th>
                            <th>{{ $entrant->championshipEntrant->driver->name }}</th>
                            <td>
                                {!! Form::text('points['.$entrant->id.']', $entrant->fastest_lap_points, ['class' => 'form-control']) !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! Form::submit('Update Fastest Lap Points', ['class' => 'btn btn-primary']) !!}
                {!! Form::close() !!}
            @else
                <p>No entrants yet - please upload results</p>
            @endif
        </div>
    </div>
</div>
