@extends('superadmin.app')

@section('content')
    <h1>Create Station</h1>
    <a href="{{ route('superadmin.company.index') }}" class="pull-right btn btn-default">Go Back</a>
<br>
    <h3>Company Details</h3>
    {!! Form::open(['action' => 'SuperadminController@companystore', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('name', 'Company Name')}}
            {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Company Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('address', 'Company Address')}}
            {{Form::text('address', '', ['class' => 'form-control', 'placeholder' => 'Company Address'])}}
        </div>
        <div class="form-group">
            {{Form::label('city', 'City')}}
            {{Form::text('city', '', ['class' => 'form-control', 'placeholder' => 'City'])}}
        </div>
        <div class="form-group">
            {{Form::label('phone', 'Phone Number')}}
            {{Form::text('phone', '', ['class' => 'form-control', 'placeholder' => 'Phone Number'])}}
        </div>
        <div class="form-group">
            {{Form::label('email', 'Email Address')}}
            {{Form::text('email', '', ['class' => 'form-control', 'placeholder' => 'Email Address'])}}
        </div>
        <div class="form-group">
            {{Form::label('status', 'Company Active Status')}}
            {{Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], 'active', ['class' => 'form-control', 'placeholder' => 'Company Active Status'])}}
        </div>
        <div class="form-group">
            {{Form::label('logo', 'Logo Image')}}
            {{Form::file('logo' )}}
        </div>
        <hr>
        <h3>Company Administrator Details</h3>
        <div class="form-group">
            {{Form::label('username', 'Admin Username')}}
            {{Form::text('username', '', ['class' => 'form-control', 'placeholder' => 'Admin Username'])}}
        </div>
        <div class="form-group">
            {{Form::label('fullname', 'Admin Full Name')}}
            {{Form::text('fullname', '', ['class' => 'form-control', 'placeholder' => 'Admin Full Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('adminphone', 'Admin Phone')}}
            {{Form::text('adminphone', '', ['class' => 'form-control', 'placeholder' => 'Admin Phone'])}}
        </div>
        <div class="form-group">
            {{Form::label('password', 'Admin Password')}}
            {{Form::password('password', ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('adminstatus', 'Company Active Status')}}
            {{Form::select('adminstatus', ['active' => 'Active', 'inactive' => 'Inactive'], 'active', ['class' => 'form-control', 'placeholder' => 'Company Active Status'])}}
        </div>
        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
    {!! Form::close() !!}
    
@endsection