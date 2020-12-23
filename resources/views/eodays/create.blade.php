@extends('layouts.app')

@section('content')
<h1> Create EOD</h1>

{!! Form::open(['action' => 'EodaysController@neweodentry', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

    <div class="form-group">
        {{Form::label('stationid', 'Station ID')}}
        {{Form::select('stationid', ['' => ''] + $stations, null, ['class' => 'form-control', 'id' => 'stationid'])}}
    </div>
    
        
    {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
{!! Form::close() !!}

@endsection