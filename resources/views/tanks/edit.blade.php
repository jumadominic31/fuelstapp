@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Edit Tank <a href="{{ route('tanks.index') }}" class="pull-right btn btn-default btn-xs">Go Back</a></div>

              {!!Form::open(['action' => ['TanksController@update', $tank->id],'method' => 'POST'])!!}
                <div class="form-group">
                    {{Form::label('tankname', 'Tank Name')}}
                    {{Form::text('tankname',$tank->tankname,['class' => 'form-control', 'disabled' => 'disabled'])}}
                </div>
                <div class="form-group">
                    {{Form::label('fueltype', 'Fuel Type')}}
                    {{Form::select('fueltype', ['' => '', 'Diesel' => 'Diesel', 'Petrol' => 'Petrol', 'Kerosene' => 'Kerosene'], $tank->fueltype, ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('stationid', 'Station ID')}}
                    {{Form::select('stationid', ['0' => ''] + $stations, $tank->stationid, ['class' => 'form-control'])}}
                </div>
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Submit')}}
              {!! Form::close() !!}

        </div>
    </div>
</div>
@endsection