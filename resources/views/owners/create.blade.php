@extends('layouts.app')

@section('content')
    <h1>Add Owner</h1>
    <a href="{{ route('owners.index') }}" class="pull-right btn btn-default">Go Back</a>
    <br>
    <div class="panel-body">
        {!! Form::open(['action' => 'OwnersController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
            <div class="form-group">
                {{Form::label('own_num', 'Owner Number *')}}
                {{Form::text('own_num', '', ['class' => 'form-control', 'placeholder' => 'Owner Number'])}}
            </div>
            <div class="form-group">
                {{Form::label('fullname', 'Full Name *')}}
                {{Form::text('fullname', '', ['class' => 'form-control', 'placeholder' => 'Owner Name'])}}
            </div>
            <div class="form-group">
                {{Form::label('phone', 'Phone Number')}}
                {{Form::text('phone', '', ['class' => 'form-control', 'placeholder' => '254722000000'])}}
            </div>
            {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
        {!! Form::close() !!}
    </div>
@endsection