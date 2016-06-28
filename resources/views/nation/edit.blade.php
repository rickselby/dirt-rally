@extends('page')

@section('header')
    <div class="page-header">
        <h1>Update nation</h1>
    </div>
@endsection

@section('content')

    {!! Form::model($nation, ['route' => ['nation.update', $nation], 'method' => 'put', 'class' => 'form-horizontal']) !!}

    @if(!$nation->name)
        <div class="form-group">
            <label for="flag" class="col-sm-2 control-label">Flag from dirtgame.com</label>
            <div class="col-sm-10">
                <img src="{{ route('nation.image', $nation) }}" />
            </div>
        </div>
    @endif

    <div class="form-group">
        {!! Form::label('name', 'Name', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('acronym', 'Acronym', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('acronym', null, ['class' => 'form-control']) !!}
            <p class="help-block">
                Two-letter country code, lowercase
            </p>
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('dirt_reference', 'Dirt Reference', ['class' => 'col-sm-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::number('dirt_reference', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            {!! Form::submit('Update Nation', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

    {!! Form::open(['route' => ['nation.destroy', $nation], 'method' => 'delete', 'class' => 'pull-right']) !!}
        {!! Form::submit('Delete nation', array('class' => 'btn btn-danger')) !!}
    {!! Form::close() !!}

@endsection
