@extends('superadmin.app')

@section('content')
    <h1>Edit Company</h1>
    <a href="{{ route('superadmin.company.index') }}" class="pull-right btn btn-default">Go Back</a>
<br>
    <h3>Company Details</h3>
    {!! Form::open(['action' => ['SuperadminController@companyupdate', $company->id],'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('name', 'Company Name')}}
            {{Form::text('name', $company->name, ['class' => 'form-control', 'placeholder' => 'Company Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('address', 'Company Address')}}
            {{Form::text('address', $company->address, ['class' => 'form-control', 'placeholder' => 'Company Address'])}}
        </div>
        <div class="form-group">
            {{Form::label('city', 'City')}}
            {{Form::text('city', $company->city, ['class' => 'form-control', 'placeholder' => 'City'])}}
        </div>
        <div class="form-group">
            {{Form::label('phone', 'Phone Number')}}
            {{Form::text('phone', $company->phone, ['class' => 'form-control', 'placeholder' => 'Phone Number'])}}
        </div>
        <div class="form-group">
            {{Form::label('email', 'Email Address')}}
            {{Form::text('email', $company->email, ['class' => 'form-control', 'placeholder' => 'Email Address'])}}
        </div>
        <div class="form-group">
            {{Form::label('logo', 'Logo Image')}}
            {{Form::file('logo')}}
        </div>
        <div class="form-group">
            {{Form::label('status', 'Company Active Status')}}
            {{Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], $company->status, ['class' => 'form-control', 'placeholder' => 'Company Active Status'])}}
        </div>
        {{Form::hidden('_method', 'PUT')}}
        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
    {!! Form::close() !!}
    
@endsection