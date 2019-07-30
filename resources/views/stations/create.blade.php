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
            {{Form::label('die_open_stock', 'Diesel Opening Stock')}}
            {{Form::text('die_open_stock', '', ['class' => 'form-control', 'placeholder' => 'Diesel Opening Stock (litres)'])}}
        </div>
        <div class="form-group">
            {{Form::label('pet_open_stock', 'Petrol Opening Stock')}}
            {{Form::text('pet_open_stock', '', ['class' => 'form-control', 'placeholder' => 'Petrol Opening Stock (litres)'])}}
        </div>
        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
    {!! Form::close() !!}
    
@endsection