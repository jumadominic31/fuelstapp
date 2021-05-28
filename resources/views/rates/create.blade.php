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
            {{Form::label('townid', 'Town')}}
            {{Form::select('townid', ['' => ''] + $towns, '', ['class' => 'form-control', 'id' => 'townid'])}}
        </div>
        <div class="form-group">
            {{Form::label('start_rate_date', 'Start Date')}}
            {{Form::text('start_rate_date', '', ['class' => 'form-control date', 'placeholder' => 'yyyy-mm-dd'])}}
        </div>
        <div class="form-group">
            {{Form::label('end_rate_date', 'End Date')}}
            {{Form::text('end_rate_date', '', ['class' => 'form-control date', 'placeholder' => 'yyyy-mm-dd'])}}
        </div>
        {{-- <div class="form-group">
            {{Form::label('fueltype', 'Fuel Type')}}
            {{Form::select('fueltype', ['Diesel' => 'Diesel', 'Petrol' => 'Petrol', 'Kerosene' => 'Kerosene'], null, ['class' => 'form-control', 'placeholder' => 'Choose fuel type'])}}
        </div>
        <div class="form-group">
            {{Form::label('sellprice', 'Selling Price')}}
            {{Form::text('sellprice', '', ['class' => 'form-control', 'placeholder' => 'Selling Price'])}}
        </div>
        <div class="form-group">
            {{Form::label('buyprice', 'Buying Price')}}
            {{Form::text('buyprice', '', ['class' => 'form-control', 'placeholder' => 'Buying Price'])}}
        </div> --}}
        <div>
            <table class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                    <th>Fuel Type</th>
                    <th>Selling Price</th>
                    <th>Buying Price</th>
                </tr>
                <tr>
                    <td>Super</td>
                    <td>{{Form::text('supersellprice', '', ['class' => 'form-control', 'placeholder' => 'Super Selling Price'])}}</td>
                    <td>{{Form::text('superbuyprice', '', ['class' => 'form-control', 'placeholder' => 'Super Buying Price'])}}</td>
                </tr>
                <tr>
                    <td>Diesel</td>
                    <td>{{Form::text('diessellprice', '', ['class' => 'form-control', 'placeholder' => 'Diesel Selling Price'])}}</td>
                    <td>{{Form::text('diesbuyprice', '', ['class' => 'form-control', 'placeholder' => 'Diesel Buying Price'])}}</td>
                </tr>
                <tr>
                    <td>Kerosene</td>
                    <td>{{Form::text('kerosellprice', '', ['class' => 'form-control', 'placeholder' => 'Kerosene Selling Price'])}}</td>
                    <td>{{Form::text('kerobuyprice', '', ['class' => 'form-control', 'placeholder' => 'Kerosene Buying Price'])}}</td>
                </tr>
            </table> 
        </div>
        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
    {!! Form::close() !!}
    
@endsection