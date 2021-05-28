@extends('layouts.app')

@section('content')
    <h1>Add Pump</h1>
    <a href="{{ route('pumps.index') }}" class="pull-right btn btn-default">Go Back</a>
<br>
    {!! Form::open(['action' => 'PumpsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('pumpname', 'Pump Name')}}
            {{Form::text('pumpname', '', ['class' => 'form-control', 'placeholder' => 'Pump Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('fueltype', 'Fuel Type')}}
            {{Form::select('fueltype', ['' => '', 'Diesel' => 'Diesel', 'Petrol' => 'Petrol', 'Kerosene' => 'Kerosene'], null, ['class' => 'form-control', 'placeholder' => 'Choose fuel type'])}}
        </div>
        <div class="form-group">
            {{Form::label('pumpreading', 'Pump Reading')}}
            {{Form::text('pumpreading', '', ['class' => 'form-control', 'placeholder' => 'Pump Reading'])}}
        </div>
        <div class="form-group">
            {{Form::label('stationid', 'Station ID')}}
            {{Form::select('stationid', ['' => ''] + $stations, null, ['class' => 'form-control', 'id' => 'stationid'])}}
        </div>
        <div class="form-group">
            {{Form::label('attendantid', 'Attendant ID (Choose Station First)')}}
            {{Form::select('attendantid',[] ,null, ['class' => 'form-control', 'id' => 'attendantid'])}}   
        </div>
        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
    {!! Form::close() !!}
    
    <script>
        $('#stationid').on('change', '', function(e){
            var stationid = e.target.value;
            
            $.get('/stationid/attendant/'+stationid, function(data){
                $('#attendantid').empty();
                $.each(data, function(index, attendantObj){
                    $('#attendantid').append('<option value="'+attendantObj.id+'">'+attendantObj.username+'</option>');
                });
            });
        });

    </script>
@endsection