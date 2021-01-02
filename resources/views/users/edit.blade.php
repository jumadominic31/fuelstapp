@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Edit User Details <a href="{{ route('users.index') }}" class="pull-right btn btn-default btn-xs">Go Back</a></div>

            <div class="panel-body">
              {!!Form::open(['action' => ['UsersController@update', $user->id],'method' => 'POST'])!!}
                <div class="form-group">
                    {{Form::label('fullname', 'Full Name *')}}
                    {{Form::text('fullname', $user->fullname, ['class' => 'form-control', 'placeholder' => 'Full name'])}}
                </div>
                <div class="form-group">
                    {{Form::label('pass1', 'Password')}}
                    {{Form::password('pass1',  ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('pass2', 'Password Again')}}
                    {{Form::password('pass2',  ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('phone', 'Phone Number *')}}
                    {{Form::text('phone', $user->phone, ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('email', 'Email')}}
                    {{Form::text('email', $user->email, ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('stationid', 'Station *')}}
                    {{Form::select('stationid', ['' => ''] + $stations, $user->stationid, ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('status', 'Status *')}}
                    {{Form::select('status', ['' => '', 'active' => 'Active', 'inactive' => 'Inactive'], $user->status, ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('usertype', 'User Type *')}}
                    {{Form::select('usertype', ['' => '', 'attendant' => 'Attendant', 'admin' => 'Admin', 'stationadmin' => 'Stationadmin'], $user->usertype, ['class' => 'form-control'])}}
                </div>
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Submit')}}
              {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection