@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Edit Vehicle <a href="{{ route('vehicles.index') }}" class="pull-right btn btn-default btn-xs">Go Back</a></div>

            <div class="panel-body">
                {!!Form::open(['action' => ['VehiclesController@update', $vehicle->id],'method' => 'POST'])!!}
                <div class="form-group">
                    {{Form::label('num_plate', 'Vehicle Number *')}}
                    {{Form::text('num_plate', $vehicle->num_plate, ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('owner_id', 'Owner Name *')}}
                    {{Form::select('owner_id', ['' => ''] + $owners, $vehicle->owner_id, ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('category', 'Category')}}
                    {{Form::text('category', $vehicle->category, ['class' => 'form-control', 'placeholder' => 'e.g. Mini bus'])}}
                </div>
                <div class="form-group">
                    {{Form::label('make', 'Make')}}
                    {{Form::text('make', $vehicle->make, ['class' => 'form-control', 'placeholder' => 'e.g. Nissan'])}}
                </div>
                <div class="form-group">
                    {{Form::label('colour', 'Colour')}}
                    {{Form::text('colour', $vehicle->colour, ['class' => 'form-control', 'placeholder' => 'e.g. White'])}}
                </div>
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Submit')}}
              {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
