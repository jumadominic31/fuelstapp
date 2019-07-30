@extends('layouts.app')

@section('content')
    <h1>Add Rate</h1>
    <a href="{{ route('rates.index') }}" class="pull-right btn btn-default">Go Back</a>
<br>
    {!! Form::open(['action' => 'RatesController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <?php
            $stations[0] = 'Select Station';
            //ksort($stations);
        ?>
        <div class="form-group">
            {{Form::label('stationid', 'Station ID')}}
            {{Form::select('stationid', $stations, null, ['class' => 'form-control', 'id' => 'stationid'])}}
        </div>
        <div class="form-group">
            {{Form::label('start_rate_date', 'Start Date')}}
            {{Form::text('start_rate_date', '', ['class' => 'form-control date', 'placeholder' => 'yyyy-mm-dd'])}}
        </div>
        <div class="form-group">
            {{Form::label('end_rate_date', 'End Date')}}
            {{Form::text('end_rate_date', '', ['class' => 'form-control date', 'placeholder' => 'yyyy-mm-dd'])}}
        </div>
        <div class="form-group">
            {{Form::label('fueltype', 'Fuel Type')}}
            {{Form::select('fueltype', ['Diesel' => 'Diesel', 'Petrol' => 'Petrol'], null, ['class' => 'form-control', 'placeholder' => 'Choose fuel type'])}}
        </div>
        <div class="form-group">
            {{Form::label('sellprice', 'Selling Price')}}
            {{Form::text('sellprice', '', ['class' => 'form-control', 'placeholder' => 'Selling Price'])}}
        </div>
        <div class="form-group">
            {{Form::label('buyprice', 'Buying Price')}}
            {{Form::text('buyprice', '', ['class' => 'form-control', 'placeholder' => 'Buying Price'])}}
        </div>
        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
    {!! Form::close() !!}
    
@endsection