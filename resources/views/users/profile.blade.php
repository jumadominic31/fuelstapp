@extends('layouts.app')

@section('content')
<div class="container">
      <!-- PANELS -->
      <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">Profile</h3>
        </div>
        <div class="panel-body">
        <table class="table table-striped">
          <tr><td>Username</td><td>{{$user['username']}}</td></tr>
          <tr><td>Full Name</td><td>{{$user['fullname']}}</td></tr>
          <tr><td>Phone Number</td><td>{{$user['phone']}}</td></tr>
          <tr><td>Station</td><td>{{$user['station']['station']}}</td></tr>
          <tr><td>User Type</td><td>{{$user['usertype']}}</td></tr>
          <tr><td>Status</td><td>{{$user['status']}}</td></tr>  

        </table>
        <div class="panel-footer"><a href="{{ route('users.resetpass', ['user' => $user->id]) }}" class="btn btn-success">Reset Password</a></div>
      </div>
</div>

@endsection