@extends('layouts.app')

@section('content')
    <h1>Create Station</h1>
    <a href="{{ route('stations.index') }}" class="pull-right btn btn-default">Go Back</a>
<br>
    {!! Form::open(['action' => 'StationsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('station', 'Station')}}
            {{Form::text('station', '', ['class' => 'form-control', 'placeholder' => 'Station Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('townid', 'Town')}}
            {{Form::select('townid', ['' => ''] + $towns, '', ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('status', 'Status')}}
            {{Form::select('status', ['' => '', '1' => 'Active', '0' => 'Inactive'], '1', ['class' => 'form-control'])}}
        </div>
        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
    {!! Form::close() !!}
    
@endsection