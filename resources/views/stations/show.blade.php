@extends('layouts.app')

@section('content')
<div class="panel-heading">
<h1> Station Details </h1> 
<h3><strong>{{$station->station}}</strong></h3>

    <a href="{{ route('stations.index') }}" class="btn btn-success">Go Back</a>

  <hr>
  <h3> Pump Details </h3> 
</div>
    @if(count($pumps) > 0)
      <?php
        $colcount = count($pumps);
        $i = 1;
      ?>
        
        <table class="table table-striped" >
            <tr>
                
                <th>Pump Name</th>
                <th>Fuel Type</th>
                <th>Attendant ID</th>
            </tr>
          
            @foreach($pumps as $pump)
            <tr>
                
                <td>{{$pump->pumpname}}</td>
                <td>{{$pump->fueltype}}</td>
                <td>{{$pump['user']['username']}}</td>
            </tr>
            @endforeach
        </table>

    @else
      <p>No Pumps To Display</p>
    @endif
<hr>


@endsection