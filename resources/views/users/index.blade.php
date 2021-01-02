@extends('layouts.app')

@section('content')
<div class="panel-heading">
<h1> User Administration </h1>

    <a href="{{ route('users.create') }}" class="btn btn-success">Add User</a>
<br>
</div>
    @if(count($users) > 0)
        <?php
            $colcount = count($users);
            $i = 1;
        ?>
        
        <table class="table table-striped" >
        <tr>

        <th>User Name</th>
        <th>Full Name</th>
        <th>Station Name</th>
        <th>User Type</th>
        <th>Status</th>
        <th></th>
        <th></th>
        </tr>
        @foreach($users as $user)
        <tr>
        
 
        <td>{{$user['username']}}</td>
        <td>{{$user['fullname']}}</td>
        <td>{{$user['station']['station']}}</td>
        <td>{{$user['usertype']}}</td>
        <td>{{$user['status']}}</td>
        <td><a class="btn btn-default" href="{{ route('users.edit', ['user' => $user->id]) }}">Edit</a></td>
        <td>
            {!!Form::open(['action' => ['UsersController@destroy', $user->id],'method' => 'POST', 'class' => 'pull-left', 'onsubmit' => 'return confirm("Are you sure?")'])!!}
              {{Form::hidden('_method', 'DELETE')}}
              {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
            {!! Form::close() !!}
        </td>
        </tr>
        @endforeach
        </table>
        {{$users->links()}}
    @else
      <p>No users To Display</p>
    @endif
@endsection