@extends('layouts.app')

@section('content')

<div class="panel-heading">
    <h1>End of Shift Reports </h1>
</div>
<div>
    <table class="table table-striped" >
        <tr>
            <th>Station Name</th>
            <th>Date</th>
            <th>Shift</th>
            <th>Details</th>
            <th>Edit/Update</th>
        </tr>
        @foreach ($shifts as $shift)
        <tr>
            <td>{{$shift['station']['station']}}</td>
            <td>{{$shift['date']}}</td>
            <td>{{$shift['shift']}}</td>
            <td><a class="btn btn-default" href="{{ route('eodays.daily.show', ['id' => $shift['id'] ]) }}">View</a></td>
            <td><a class="btn btn-primary" href="{{ route('eodays.edit.eodentry', ['id' => $shift['id'] ]) }}">Edit</a></td>
        </tr>
        @endforeach
    </table>
    {{$shifts->links()}}
</div>

@endsection