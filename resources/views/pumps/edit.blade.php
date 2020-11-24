@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Edit Pump <a href="{{ route('pumps.index') }}" class="pull-right btn btn-default btn-xs">Go Back</a></div>

              {!!Form::open(['action' => ['PumpsController@update', $pump->id],'method' => 'POST'])!!}
                <div class="form-group">
                    {{Form::label('pumpname', 'Pump Name')}}
                    {{Form::text('pumpname',$pump->pumpname,['class' => 'form-control', 'disabled' => 'disabled'])}}
                </div>
                <div class="form-group">
                    {{Form::label('fueltype', 'Fuel Type')}}
                    {{Form::select('fueltype', ['Diesel' => 'Diesel', 'Petrol' => 'Petrol'], $pump->fueltype, ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('stationid', 'Station ID')}}
                    {{Form::select('stationid', ['0' => ''] + $stations, $pump->stationid, ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('attendantid', 'Attendant ID (Choose Station First)')}}
                    {{Form::select('attendantid', ['' => ''] + $users, $pump->attendantid, ['class' => 'form-control', 'id' => 'attendantid'])}}   
                </div>
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Submit')}}
              {!! Form::close() !!}

        </div>
    </div>
</div>

<script>
    $('#stationid').on('change',function(e){
        var stationid = e.target.value;

        $.get('/stationid/attendant/'+stationid, function(data){
            $('#attendantid').empty();
            $.each(data, function(index, attendantObj){
                $('#attendantid').append('<option value="'+attendantObj.id+'">'+attendantObj.username+'</option>');
            });
        });
    });
//
</script>
@endsection