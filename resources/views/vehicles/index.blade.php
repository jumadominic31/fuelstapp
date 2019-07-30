@extends('layouts.app')

@section('content')
  <h1> Vehicles Administration </h1> 
  <div>
    <a href="{{ route('vehicles.create') }}" class="btn btn-success">Add Vehicle</a>
    <a class="pull-right btn btn-default" href="{{ route('vehicles.index') }}">Reset</a> 
  </div>
  <h3>Total Registered Vehicles : {{$count}}</h3>
  @if(count($vehicles) > 0)
    <?php
      $colcount = count($vehicles);
      $i = 1;
    ?>
<h4>Filter</h4>
<input type="checkbox" autocomplete="off" onchange="checkfilter(this.checked);"/>
<div id="filteroptions" style="display: none ;">
  {!! Form::open(['action' => 'VehiclesController@index', 'method' => 'GET', 'enctype' => 'multipart/form-data']) !!}
    <table class="table">
      <tbody>
        <tr>
          <td><div class="form-group">
              {{Form::label('num_plate', 'Vehicle Num Plate')}}
              {{Form::text('num_plate', '', ['class' => 'form-control'])}}
          </div></td>
          <td><div class="form-group">
              {{Form::label('owner_id', 'Name')}}
              {{Form::select('owner_id', ['' => ''] + $owners,'' , ['class' => 'form-control'])}}
          </div></td>
          <td></td>
        </tr>
      </tbody>
    </table>
    {{Form::submit('Submit', ['class'=>'btn btn-primary', 'name' => 'submitBtn'])}}
</div>
<hr>
  <table class="table table-striped" >
    <tr>
      <th>Number Plate</th>
      <th>Owner Name</th>
      <th>Category</th>
      <th>Make</th>
      <th>Colour</th>
      <th></th>
    </tr>
    @foreach($vehicles as $vehicle)
    <tr>
      <td>{{$vehicle->num_plate}}</td>
      <td>{{$vehicle->owner->fullname}}</td>
      <td>{{$vehicle->category}}</td>
      <td>{{$vehicle->make}}</td>
      <td>{{$vehicle->colour}}</td>
      <td><a class="btn btn-default" href="{{ route('vehicles.edit', ['vehicle' => $vehicle->id ]) }}"> Edit</a></td>
    </tr>
    @endforeach
  </table>
  {{ $vehicles->appends(request()->input())->links() }}

  @else
    <p>No Vehicles To Display</p>
  @endif
@endsection