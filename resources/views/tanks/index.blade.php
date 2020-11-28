@extends('layouts.app')

@section('content')
<div class="panel-heading"><h1> Tanks Administration </h1> 
<br>

    <a href="{{ route('tanks.create') }}" class="btn btn-success">Add Tank</a>
<br>
</div>
    @if(count($tanks) > 0)
      <?php
        $colcount = count($tanks);
        $i = 1;
      ?>
        
          <table class="table table-striped" >
          <tr>
          
          <th class="col-xs-2">Tank Name</th>
          <th class="col-xs-2">Fuel Type</th>
          <th class="col-xs-2">Station</th>
          <th class="col-xs-1"></th>
          <th class="col-xs-1"></th>
          </tr>
          @foreach($tanks as $tank)
          <tr>
          
          
          <td class="col-xs-2">{{$tank['tankname']}}</td>
          <td class="col-xs-2">{{$tank['fueltype']}}</td>
          <td class="col-xs-2">{{$tank['station']['station']}}</td>
          <td class="col-xs-1"><span class="center-block"><a class="pull-right btn btn-default" href="{{ route('tanks.edit', ['tank' => $tank->id ]) }}">Edit</a></span></td>
          <td class="col-xs-1"><span class="center-block">
            {!!Form::open(['action' => ['TanksController@destroy', $tank->id],'method' => 'POST', 'class' => 'pull-left', 'onsubmit' => 'return confirm("Are you sure?")'])!!}
              {{Form::hidden('_method', 'DELETE')}}
              {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
            {!! Form::close() !!}
          </span></td>
          
          </tr>
            @endforeach
          </table>
          {{$tanks->links()}}

    @else
      <p>No tanks To Display</p>
    @endif
@endsection