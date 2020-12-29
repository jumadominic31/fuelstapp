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
        <th>Date</th>
        <th>Vehicle</th>
        <th>Owner</th>
        <th style="text-align: right;">Opening Bal</th>
        <th style="text-align: right;">Purchase</th>
        <th style="text-align: right;">Payment</th>
        <th style="text-align: right;">Closing Bal</th>
      </tr>
      @foreach($collections as $collection)
      <tr>
        <td>{{$collection['created_at']}}</td>
        <td>{{$collection['vehicle']['num_plate']}}</td>
        <td>{{$collection['owner']['fullname']}}</td>
        <td style="text-align: right;">{{number_format($collection['opening'], 2)}}</td>
        <td style="text-align: right;">{{number_format($collection['purchase'], 2)}}</td>
        <td style="text-align: right;">{{number_format($collection['payment'], 2)}}</td>
        <td style="text-align: right;">{{number_format($collection['closing'], 2)}}</td>
      </tr>
      @endforeach
    </table>
    {{$collections->links()}}

  @else
    <p>No collections To Display</p>
  @endif
@endsection