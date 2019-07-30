@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Edit Rate <a href="{{ route('rates.index') }}" class="pull-right btn btn-default btn-xs">Go Back</a></div>

            <div class="panel-body">
              {!!Form::open(['action' => ['RatesController@update', $rate->id],'method' => 'POST'])!!}
                <div class="form-group">
                    {{Form::label('start_rate_date', 'Start Date')}}
                    {{Form::text('start_rate_date', $rate->start_rate_date, ['class' => 'form-control date', 'placeholder' => 'yyyy-mm-dd'])}}
                </div>
                <div class="form-group">
                    {{Form::label('end_rate_date', 'End Date')}}
                    {{Form::text('end_rate_date', $rate->end_rate_date, ['class' => 'form-control date', 'placeholder' => 'yyyy-mm-dd'])}}
                </div>
                <div class="form-group">
                    {{Form::label('fueltype', 'Fuel Type')}}
                    {{Form::select('fueltype', [$rate->fueltype], $rate->fueltype, ['class' => 'form-control', 'disabled' => 'disabled'])}}
                </div>
                <div class="form-group">
                    {{Form::label('sellprice', 'Selling Price')}}
                    {{Form::text('sellprice',$rate->sellprice, ['class' => 'form-control', 'placeholder' => 'Selling Price'])}}
                </div>
                <div class="form-group">
                    {{Form::label('buyprice', 'Buying Price')}}
                    {{Form::text('buyprice', $rate->buyprice, ['class' => 'form-control', 'placeholder' => 'Buying Price'])}}
                </div>
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Submit')}}
              {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
