@extends('layouts.app')

@section('content')
<h1> Tank Dip Readings</h1>
{!! Form::open(['action' => 'EodaysController@tankstore', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
    <input type="hidden" id="shift_id" name="shift_id" value="{{$shift_id}}">
    <div class="form-group">
        {{Form::label('stationid', 'Station ID')}}
        {{Form::select('stationid', ['' => ''] + $stations, null, ['class' => 'form-control', 'id' => 'stationid'])}}
    </div>
    <div class="form-group">
        {{Form::label('shift_date', 'Shift Date')}}
        {{Form::text('shift_date', $shift_date, ['class' => 'form-control', 'readonly' => 'true'])}}
    </div>
    <div class="form-group">
        {{Form::label('shiftid', 'Shift')}}
        {{Form::text('shiftid', $shift, ['class' => 'form-control', 'readonly' => 'true'])}}
    </div>
    <div>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th style="display:none;">Tank ID</th>
                <th>Tank Name</th>
                <th>Previous Reading</th>
                <th>Purchased</th>
                <th>New Reading</th>
            </tr>
            @foreach($tankreadings as $tank)
            <tr>
                <td style="display:none;">{{Form::text('id_'.$tank['tank_id'], $tank['tank_id'] , ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('name_'.$tank['tank_id'], $tank['tank']['tankname'] , ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('prev_'.$tank['tank_id'] , $tank['reading'] , ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('purc_'.$tank['tank_id'] , '' , ['class' => 'form-control'])}}</td>
                <td>{{Form::text('new_'.$tank['tank_id'] , '' , ['class' => 'form-control'])}}</td>
            </tr>
            @endforeach
        </table>
    </div>
    
    {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
{!! Form::close() !!}
@endsection