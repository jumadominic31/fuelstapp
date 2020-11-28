@extends('layouts.app')

@section('content')
    <h1>Add Tank</h1>
    <a href="{{ route('tanks.index') }}" class="pull-right btn btn-default">Go Back</a>
<br>
    {!! Form::open(['action' => 'TanksController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('tankname', 'Tank Name')}}
            {{Form::text('tankname', '', ['class' => 'form-control', 'placeholder' => 'Tank Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('fueltype', 'Fuel Type')}}
            {{Form::select('fueltype', ['' => '', 'Diesel' => 'Diesel', 'Petrol' => 'Petrol', 'Kerosene' => 'Kerosene'], null, ['class' => 'form-control', 'placeholder' => 'Choose fuel type'])}}
        </div>
        <div class="form-group">
            {{Form::label('stationid', 'Station ID')}}
            {{Form::select('stationid', ['' => ''] + $stations, null, ['class' => 'form-control', 'id' => 'stationid'])}}
        </div>
        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
    {!! Form::close() !!}
    
@endsection