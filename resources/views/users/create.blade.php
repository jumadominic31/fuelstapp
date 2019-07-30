@extends('layouts.app')

@section('content')
    <h1>Create New User</h1>
    <a class="pull-right btn btn-default" href="{{ route('users.index') }}">Go Back</a>
    <br>
    {!! Form::open(['action' => 'UsersController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('username', 'Username')}}
            {{Form::text('username', '', ['class' => 'form-control', 'placeholder' => 'Username'])}}
        </div>
        <div class="form-group">
            {{Form::label('fullname', 'Full Name')}}
            {{Form::text('fullname', '', ['class' => 'form-control', 'placeholder' => 'Full name'])}}
        </div>
        <div class="form-group">
            {{Form::label('phone', 'Phone Number')}}
            {{Form::text('phone', '', ['class' => 'form-control', 'placeholder' => 'Phone Number'])}}
        </div>
        <div class="form-group">
            {{Form::label('email', 'Email')}}
            {{Form::text('email', '', ['class' => 'form-control', 'placeholder' => 'Email Address'])}}
        </div>
        <div class="form-group">
            {{Form::label('stationid', 'Station ID')}}
            {{Form::select('stationid', $stations, null, ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('status', 'Status')}}
            {{Form::select('status', ['Active' => 'Active', 'Inactive' => 'Inactive'], 'Active', ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('usertype', 'User Type')}}
            {{Form::select('usertype', ['Attendant' => 'Attendant', 'Admin' => 'Admin', 'Stationadmin' => 'Stationadmin'], 'Attendant', ['class' => 'form-control'])}}
        </div>
        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
    {!! Form::close() !!}
    
@endsection