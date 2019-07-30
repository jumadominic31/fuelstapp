@extends('layouts.app')

@section('content')
    <h1>Add Vehicle</h1>
    <a href="{{ route('vehicles.index') }}" class="pull-right btn btn-default">Go Back</a>
    <br>
    <div class="panel-body">
        {!! Form::open(['action' => 'VehiclesController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
            <div class="form-group">
                {{Form::label('num_plate', 'Vehicle Number *')}}
                {{Form::text('num_plate', '', ['class' => 'form-control', 'placeholder' => 'Vehicle Number - NO SPACE'])}}
            </div>
            <div class="form-group">
                {{Form::label('owner_id', 'Owner Name *')}}
                {{Form::select('owner_id', ['' => ''] + $owners,'' , ['class' => 'form-control'])}}
            </div>
            <div class="form-group">
                {{Form::label('category', 'Category')}}
                {{Form::text('category', '', ['class' => 'form-control', 'placeholder' => 'e.g. Mini bus'])}}
            </div>
            <div class="form-group">
                {{Form::label('make', 'Make')}}
                {{Form::text('make', '', ['class' => 'form-control', 'placeholder' => 'e.g. Nissan'])}}
            </div>
            <div class="form-group">
                {{Form::label('colour', 'Colour')}}
                {{Form::text('colour', '', ['class' => 'form-control', 'placeholder' => 'e.g. White'])}}
            </div>
            {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
        {!! Form::close() !!}
    </div>
@endsection