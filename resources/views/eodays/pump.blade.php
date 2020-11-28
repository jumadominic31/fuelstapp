@extends('layouts.app')

@section('content')
<h1> Pump Readings</h1>

{!! Form::open(['action' => 'EodaysController@pumpstore', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

    <input type="hidden" id="shift_id" name="shift_id" value="{{$shift_id}}">
    <div class="form-group">
        {{Form::label('stationid', 'Station ID')}}
        {{Form::select('stationid', ['' => ''] + $stations, null, ['class' => 'form-control', 'id' => 'stationid'])}}
    </div>
    <div class="form-group">
        {{Form::label('shift_date', 'Shift Date')}}
        {{Form::text('shift_date', $shift_date , ['class' => 'form-control date', 'readonly' => 'true'])}}
    </div>
    <div class="form-group">
        {{Form::label('shift', 'Shift')}}
        {{Form::text('shift', $shift, ['class' => 'form-control date', 'readonly' => 'true'])}}
    </div>
    <div>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th style="display:none;">Pump ID</th>
                <th>Pump Name</th>
                <th>Previous Reading</th>
                <th>Returned</th>
                <th>New Reading</th>
                <th>Attendant</th>
            </tr>
            @foreach($pumpreadings as $pump)
            <tr>
                <td style="display:none;">{{Form::text('id_'.$pump['pump_id'], $pump['pump_id'] , ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('name_'.$pump['pump_id'], $pump['pump']['pumpname'] , ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('prev_'.$pump['pump_id'], $pump['reading'] , ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('returned_'.$pump['pump_id'] , '' , ['class' => 'form-control'])}}</td>
                <td>{{Form::text('new_'.$pump['pump_id'] , '' , ['class' => 'form-control'])}}</td>
                <td>{{Form::select('att_'.$pump['pump_id'] , ['' => ''] + $attendants, null , ['class' => 'form-control'])}}</td>
            </tr>
            @endforeach
        </table>
    </div>
    
    {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
{!! Form::close() !!}

@endsection