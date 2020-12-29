@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Edit Product <a href="{{ route('products.index') }}" class="pull-right btn btn-default btn-xs">Go Back</a></div>

              {!!Form::open(['action' => ['ProductsController@update', $product->id],'method' => 'POST'])!!}
                <div class="form-group">
                    {{Form::label('name', 'Product Name')}}
                    {{Form::text('name',$product->name,['class' => 'form-control'])}}
                </div>
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Submit')}}
              {!! Form::close() !!}

        </div>
    </div>
</div>
@endsection