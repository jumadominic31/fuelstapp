@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Edit Station <a href="{{ route('stations.index') }}" class="pull-right btn btn-default btn-xs">Go Back</a></div>

            <div class="panel-body">
              {!!Form::open(['action' => ['StationsController@update', $station->id],'method' => 'POST'])!!}
                {{Form::text('station',$station->station,['class' => 'form-control','placeholder' => 'Station Name'])}}
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Submit')}}
              {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
