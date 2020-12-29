@extends('layouts.app')

@section('content')
<div class="panel-heading"><h1> Products Administration </h1> 
<br>

    <a href="{{ route('products.create') }}" class="btn btn-success">Add Product</a>
<br>
</div>
    @if(count($products) > 0)
      <?php
        $colcount = count($products);
        $i = 1;
      ?>
        
          <table class="table table-striped" >
          <tr>
          
          <th class="col-xs-2">Product Name</th>
          <th class="col-xs-1"></th>
          <th class="col-xs-1"></th>
          </tr>
          @foreach($products as $product)
          <tr>
          
          
          <td class="col-xs-2">{{$product['name']}}</td>
          <td class="col-xs-1"><span class="center-block"><a class="pull-right btn btn-default" href="{{ route('products.edit', ['product' => $product->id ]) }}">Edit</a></span></td>
          <td class="col-xs-1"><span class="center-block">
            {!!Form::open(['action' => ['ProductsController@destroy', $product->id],'method' => 'POST', 'class' => 'pull-left', 'onsubmit' => 'return confirm("Are you sure?")'])!!}
              {{Form::hidden('_method', 'DELETE')}}
              {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
            {!! Form::close() !!}
          </span></td>
          
          </tr>
            @endforeach
          </table>
          {{$products->links()}}

    @else
      <p>No products To Display</p>
    @endif
@endsection