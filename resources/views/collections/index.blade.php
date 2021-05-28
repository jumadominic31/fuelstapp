@extends('layouts.app')

@section('content')
<div class="panel-heading"><h1>Credit Collections Report</h1> 
<br>

    <a href="{{ route('collections.create') }}" class="btn btn-success">Enter Collection</a>
<br>
</div>
  @if(count($collections) > 0)
    <?php
      $colcount = count($collections);
      $i = 1;
    ?>
    
    <table class="table table-striped" >
      <tr>
        <th>Owner</th>
        <th>Balance</th>
        <th>Detailed Transactions</th>
      </tr>
      @foreach($collections as $collection)
      <tr>
        <td>{{$collection->fullname}}</td>
        <td style="text-align: right;">{{number_format($collection->closing, 2)}}</td>
        <td><a class="btn btn-default" href="{{ route('collections.show', ['id' => $collection->owner_id ]) }}">Details</a></td>
      </tr>
      @endforeach
    </table>
    {{$collections->links()}}

  @else
    <p>No collections To Display</p>
  @endif
@endsection