@extends('layouts.app')

@section('content')
<h1> Actual Collections</h1>
{!! Form::open(['action' => 'EodaysController@collectionstore', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
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
                <th style="display:none;">Attendant ID</th>
                <th>Attendant</th>
                <th>Cash</th>
                <th>Mpesa</th>
                <th>Credit</th>
                <th>Visa</th>
                <th>Total</th>
            </tr>
            @foreach($attendants as $att)
            <tr>
                <td style="display:none;">{{Form::text('id_'.$att['attendant_id'], $att['attendant_id'] , ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('name_'.$att['attendant_id'], $att['user']['fullname'] , ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('cash_'.$att['attendant_id'] , '' , ['class' => 'form-control'])}}</td>
                <td>{{Form::text('mpesa_'.$att['attendant_id'] , '' , ['class' => 'form-control'])}}</td>
                <td>{{Form::text('credit_'.$att['attendant_id'] , '' , ['class' => 'form-control'])}}</td>
                <td>{{Form::text('visa_'.$att['attendant_id'] , '' , ['class' => 'form-control'])}}</td>
                <td>Total</td>
            </tr>
            @endforeach
        </table> 
    </div>
    
    {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
{!! Form::close() !!}

            

@endsection