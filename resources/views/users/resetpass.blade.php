@extends('layouts.app')

@section('content')
    <h1>Reset Password</h1>
    <a href="{{ route('users.profile') }}" class="btn btn-success">Go Back</a>
    {!! Form::open(['action' => 'UsersController@resetpass', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('curr_password', 'Current Password')}}
            {{Form::password('curr_password',  ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('new_password_1', 'New Password')}}
            {{Form::password('new_password_1',  ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('new_password_2', 'Confirm New Password')}}
            {{Form::password('new_password_2',  ['class' => 'form-control'])}}
        </div>

        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
    {!! Form::close() !!}

@endsection