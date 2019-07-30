@extends('layouts.app')

@section('content')
<div class="panel-heading"><h1> Pumps Administration </h1> 
<br>

    <a href="{{ route('pumps.create') }}" class="btn btn-success">Add Pump</a>
<br>
</div>
    @if(count($pumps) > 0)
      <?php
        $colcount = count($pumps);
        $i = 1;
      ?>
        
          <table class="table table-striped" >
          <tr>
          
          <th class="col-xs-2">Pump Name</th>
          <th class="col-xs-2">Fuel Type</th>
          <th class="col-xs-2">Station</th>
          <th class="col-xs-2">Attendant</th>
          <th class="col-xs-1"></th>
          <th class="col-xs-1"></th>
          </tr>
          @foreach($pumps as $pump)
          <tr>
          
          
          <td class="col-xs-2">{{$pump['pumpname']}}</td>
          <td class="col-xs-2">{{$pump['fueltype']}}</td>
          <td class="col-xs-2">{{$pump['station']['station']}}</td>
          <td class="col-xs-2">{{$pump['user']['username']}}</td>
          <td class="col-xs-1"><span class="center-block"><a class="pull-right btn btn-default" href="{{ route('pumps.edit', ['pump' => $pump->id ]) }}">Edit</a></span></td>
          <td class="col-xs-1"><span class="center-block">
            {!!Form::open(['action' => ['PumpsController@destroy', $pump->id],'method' => 'POST', 'class' => 'pull-left', 'onsubmit' => 'return confirm("Are you sure?")'])!!}
              {{Form::hidden('_method', 'DELETE')}}
              {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
            {!! Form::close() !!}
          </span></td>
          
          </tr>
            @endforeach
          </table>
          {{$pumps->links()}}

    @else
      <p>No pumps To Display</p>
    @endif
@endsection