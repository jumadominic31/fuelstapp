@extends('layouts.app')

@section('content')

<div class="panel-heading"><h1> Daily Report </h1>
<span class="pull-left">
    @if(Auth::user()->usertype == 'stationadmin')
      <a href="{{ route('readings.create') }}" class="btn btn-success">Create End of Day Report</a>
    @endif
  </span>

</div>
    @if(count($eodays) > 0)
      <?php
        $colcount = count($eodays);
        $i = 1;
      ?>
        
          <table class="table table-striped" >
          <tr>
          <th>Id</th>
          <th>Station Id</th>
          <th>Fuel Type</th>
          <th>Created Date</th>
          <th></th>
          <th></th>
          </tr>
          @foreach($eodays as $eoday)
          <tr>
          
          <td>{{$eoday['id']}}</td>
          <td>{{$eoday['station']['station']}}</td>
          <td>{{$eoday['fueltype']}}</td>
          <td>{{$eoday['created_at']}}</td>
          <td><a class="pull-right btn btn-default" href="{{ route('eodays.show', ['eoday' => $eoday['id'] ]) }}">View Details</a></td>
         
          </tr>
            @endforeach
          </table>
          {{$eodays->links()}}

    @else
      <p>No End of day reports to display</p>
    @endif
@endsection