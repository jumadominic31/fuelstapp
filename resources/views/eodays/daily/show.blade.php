@extends('layouts.app')


@section('content')

<h1>Shift Details </h1>

<a href="{{ route('eodays.daily.index') }}" class="btn btn-default">Go Back</a><br><br>


{!! Form::open(['action' => ['EodaysController@showeodentry', $shift['id'] ], 'method' => 'GET', 'enctype' => 'multipart/form-data']) !!}

    {{Form::submit('DownloadRpt', ['class'=>'btn btn-primary', 'name' => 'submitBtn', 'formtarget' => '_blank'])}}

{!! Form::close() !!}
<br>
@include('eodays.daily.shiftdetails')

@endsection