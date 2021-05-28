@extends('layouts.app')

@section('content')
<div class="panel-heading"><h1> Stations Administration </h1> 

    <a href="{{ route('stations.create') }}" class="btn btn-success">Add Station</a>
<br>
</div>
    @if(count($stations) > 0)
      <?php
        $colcount = count($stations);
        $i = 1;
      ?>
        
          <table class="table table-striped" >
          <tr>
          
          <th>Station Name</th>
          <th>Town</th>
          <th>Status</th>
          <th></th>
          <th></th>
          <th></th>
          </tr>
          @foreach($stations as $station)
          <tr>
          
          
          <td>{{$station->station}}</td>
          <td>{{$station->town->name}}</td>
          <td>{{$station->status == '1' ? 'Active' : 'Inactive'}}</td>
          <td><a class="btn btn-default" href="{{ route('stations.show', ['station' => $station->id ]) }}">Details</a></td>
          <td><a class="btn btn-default" href="{{ route('stations.edit', ['station' => $station->id ]) }}"> Edit</a></td>
          <td>
            {!!Form::open(['action' => ['StationsController@destroy', $station->id],'method' => 'POST', 'class' => 'pull-left', 'onsubmit' => 'return confirm("Are you sure?")'])!!}
              {{Form::hidden('_method', 'DELETE')}}
              {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
            {!! Form::close() !!}
          </td>
          
          </tr>
            @endforeach
          </table>
          {{$stations->links()}}

    @else
      <p>No stations To Display</p>
    @endif
@endsection