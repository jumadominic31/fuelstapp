@extends('layouts.app')

@section('content')
    <h1>Add Product</h1>
    <a href="{{ route('products.index') }}" class="pull-right btn btn-default">Go Back</a>
<br>
    {!! Form::open(['action' => 'ProductsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('name', 'Product Name')}}
            {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Product Name'])}}
        </div>
        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
    {!! Form::close() !!}
@endsection