@extends('layouts.app')

@section('content')
<div class="panel-heading"><h1>Credit Collections Report</h1> 
  <h3>Member: {{$owner_name}}</h3>
<br>

    <a href="{{ route('collections.index') }}" class="btn btn-success">Back to Main Page</a>
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
        <th>Owner</th>
        <th>Vehicle</th>
        <th>Opening</th>
        <th>Purchase</th>
        <th>Payment</th>
        <th>Balance</th>
      </tr>
      @foreach($collections as $collection)
      <tr>
        <td>{{$collection->created_at}}</td>
        <td>{{$collection->owner->fullname}}</td>
        <td>
          @if ($collection->vehicle_id != NULL)
            {{$collection->vehicle->num_plate}}
          @endif
        </td>
        <td style="text-align: right;">{{number_format($collection->opening, 2)}}</td>
        <td style="text-align: right;">{{number_format($collection->purchase, 2)}}</td>
        <td style="text-align: right;">{{number_format($collection->payment, 2)}}</td>
        <td style="text-align: right;">{{number_format($collection->closing, 2)}}</td>
      </tr>
      @endforeach
    </table>
    {{$collections->links()}}

  @else
    <p>No collections To Display</p>
  @endif
@endsection