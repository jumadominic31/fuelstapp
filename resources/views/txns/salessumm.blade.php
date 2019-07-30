@extends('layouts.app')

@section('content')
<h1> Summary per Attendant </h1>

<a class="pull-right btn btn-default" href="{{ route('txns.salessumm.index') }}">Reset</a>
<h3> Filter </h3>
<input type="checkbox" autocomplete="off" onchange="checkfilter(this.checked);"/>
<div id="filteroptions" style="display: none ;">
    {!! Form::open(['action' => 'TxnsController@salessumm', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        @if (Auth::user()->usertype == 'admin')
            <div class="form-group">
                {{Form::label('station', 'Station')}}
                {{Form::select('station', $stations, null, ['class' => 'form-control', 'optional' => 'Choose Station'])}}
            </div>
        @endif
        <div class="form-group">
            {{Form::label('summ_date', 'Date')}}
            {{Form::text('summ_date', '', ['class' => 'form-control date', 'placeholder' => 'Choose Date yyyy-mm-dd'])}}
        </div>
    {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
</div>
<hr>
<div class="row">

        
    @if(count($txns) > 0)
        <?php
            $colcount = count($txns);
            $i = 1;
        ?>
         
        <table class="table table-striped" >
        <tr>
        <th>Attendant</th>
        <th>Fuel Type</th>
        <th>Payment Channel</th>
        <th>Total Volume</th>
        <th>Total Sales</th>
        </tr>
        @foreach($txns as $txn)
        <tr>
        
        <td>{{$txn['user']['username']}}</td>
        <td>{{$txn['fueltype']}}</td>
        <td>{{$txn['paymethod']}}</td>
        <td>{{$txn['total_vol']}}</td>
        <td>{{$txn['total_sales']}}</td>
        </tr>
        @endforeach

        </table>
        
    @else
      <p>No txns To Display</p>
    @endif
</div>
@endsection