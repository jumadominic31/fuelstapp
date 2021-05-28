@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Edit Station <a href="{{ route('stations.index') }}" class="pull-right btn btn-default btn-xs">Go Back</a></div>

            <div class="panel-body">
              {!!Form::open(['action' => ['StationsController@update', $station->id],'method' => 'POST'])!!}
                <div class="form-group">
                  {{Form::label('station', 'Station')}}
                  {{Form::text('station', $station->station, ['class' => 'form-control', 'placeholder' => 'Station Name'])}}
                </div>
                <div class="form-group">
                  {{Form::label('townid', 'Town')}}
                  {{Form::select('townid', ['' => ''] + $towns, $station->town_id, ['class' => 'form-control'])}}
              </div>
                <div class="form-group">
                    {{Form::label('status', 'Status')}}
                    {{Form::select('status', ['' => '', '1' => 'Active', '0' => 'Inactive'] , $station->status,['class' => 'form-control'])}}
                </div>
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Submit')}}
              {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
