@extends('page')

@section('content')

    {!! Form::open(['route' => ['assetto-corsa.championship.event.destroy', $event->championship, $event], 'method' => 'delete', 'class' => 'form-inline']) !!}
        <a class="btn btn-small btn-warning"
           href="{{ route('assetto-corsa.championship.event.edit', [$event->championship, $event]) }}">Edit Event</a>
        {!! Form::submit('Delete Event', array('class' => 'btn btn-danger')) !!}
    {!! Form::close() !!}

    <br />

    <h2>Sessions</h2>
    <p>
        <a class="btn btn-small btn-info"
           href="{{ route('assetto-corsa.championship.event.session.create', [$event->championship, $event]) }}">Add a new session</a>
    </p>

    @if (count($event->sessions) == 0)
        {!! Form::open(['route' => ['assetto-corsa.championship.event.copy-sessions', $event->championship, $event], 'class' => 'form-horizontal']) !!}
        <div class="form-group">
            {!! Form::label('from-event', 'Copy sessions from', ['class' => 'col-sm-3 control-label']) !!}
            <div class="col-sm-9">
                {!! Form::select('from-event', $otherEvents, null, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-3"></div>
            <div class="col-sm-9">
                {!! Form::submit('Copy Sessions', ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
        {!! Form::close() !!}
    @endif

    <ul>
        @forelse($event->sessions AS $session)
            <li>
                <a href="{{ route('assetto-corsa.championship.event.session.show', [$event->championship, $event, $session]) }}">
                    {{ $session->name }}
                </a>
            </li>
        @empty
            <li>No sessions</li>
        @endforelse
    </ul>

@endsection