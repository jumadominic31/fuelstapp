@extends('layouts.app')

@section('content')
<h1> Transactions </h1>
<a class="pull-left btn btn-default" href="{{ route('loyalty.members') }}">Go back</a><br>

@include('loyalty.details')

@endsection