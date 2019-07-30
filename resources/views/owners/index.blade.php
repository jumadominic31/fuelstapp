@extends('layouts.app')

@section('content')
  <h1> Owners Administration </h1> 
  <div>
    <a href="{{ route('owners.create') }}" class="btn btn-success">Add Owner</a>
    <a class="pull-right btn btn-default" href="{{ route('owners.index') }}">Reset</a> 
  </div>
  <h3>Total Registered Owners : {{$count}}</h3>
  @if(count($owners) > 0)
    <?php
      $colcount = count($owners);
      $i = 1;
    ?>
<h4>Filter</h4>
<input type="checkbox" autocomplete="off" onchange="checkfilter(this.checked);"/>
<div id="filteroptions" style="display: none ;">
  {!! Form::open(['action' => 'OwnersController@index', 'method' => 'GET', 'enctype' => 'multipart/form-data']) !!}
    <table class="table">
      <tbody>
        <tr>
          <td><div class="form-group">
              {{Form::label('own_num', 'Owner Num')}}
              {{Form::text('own_num', '', ['class' => 'form-control'])}}
          </div></td>
          <td><div class="form-group">
              {{Form::label('fullname', 'Name')}}
              {{Form::text('fullname', '', ['class' => 'form-control'])}}
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
      <th>Owner Num</th>
      <th>Full Name</th>
      <th>Phone</th>
      <th></th>
    </tr>
    @foreach($owners as $owner)
    <tr>
      <td>{{$owner->own_num}}</td>
      <td>{{$owner->fullname}}</td>
      <td>{{$owner->phone}}</td>
      <td><a class="btn btn-default" href="{{ route('owners.edit', ['owner' => $owner->id ]) }}"> Edit</a></td>
    </tr>
    @endforeach
  </table>
  {{ $owners->appends(request()->input())->links() }}

  @else
    <p>No Owners To Display</p>
  @endif
@endsection