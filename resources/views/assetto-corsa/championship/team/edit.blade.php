@extends('page')

@push('stylesheets')
<link href="{{ route('assetto-corsa.championship.entrant.css', $championship) }}" rel="stylesheet" />
@endpush

@section('header')
    <div class="page-header">
        <h1>Update Team</h1>
    </div>
@endsection

@section('content')

    {!! Form::model($team, ['route' => ['assetto-corsa.championship.team.update', $championship, $team], 'method' => 'put', 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('name', 'Full Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('short_name', 'Short Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('short_name', null, ['class' => 'form-control']) !!}
            <p class="help-block">
                Keep it short, results are already busy...
            </p>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('ac_car_id', 'Car', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('ac_car_id', \App\Models\AssettoCorsa\AcCar::pluck('full_name', 'id'), null, ['class' => 'form-control', 'placeholder' => 'No Car']) !!}
            <p class="help-block">
                If the team is running a single car, pick it from the list
            </p>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            {!! Form::submit('Update Entrant', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection