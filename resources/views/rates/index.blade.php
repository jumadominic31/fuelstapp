@extends('layouts.app')

@section('content')
<div class="panel-heading"><h1> Rates </h1> 

    <a href="{{ route('rates.create') }}" class="btn btn-success">Add Rate</a>
<br>
</div>
    @if(count($rates) > 0)
      <?php
        $colcount = count($rates);
        $i = 1;
      ?>
        
          <table class="table table-striped" >
          <tr>
          <th>Station</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Fueltype</th>
          <th>Selling Price</th>
          <th>Buying Price</th>
          <th></th>
          </tr>
          @foreach($rates as $rate)
          <tr>
          <td>{{$rate['station']['station']}}</td>
          <td>{{$rate->start_rate_date}}</td>
          <td>{{$rate->end_rate_date}}</td>
          <td>{{$rate->fueltype}}</td>
          <td>{{$rate->sellprice}}</td>
          <td>{{$rate->buyprice}}</td>
          <td><a class="pull-right btn btn-default" href="{{ route('rates.edit', ['rate' => $rate->id ]) }}">Edit</a></td>
                    
          </tr>
            @endforeach
          </table>
          {{$rates->links()}}

    @else
      <p>No Rates To Display</p>
    @endif
@endsection