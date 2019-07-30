@extends('layouts.app')

@section('content')
<h1> Transactions </h1>

<a class="pull-right btn btn-default" href="/txns">Reset</a>
<h3> Filter </h3>
<input type="checkbox" autocomplete="off" onchange="checkfilter(this.checked);"/>
<div id="filteroptions" style="display: none ;">
    {!! Form::open(['action' => 'TxnsController@index', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
    <table class="table">
      <tbody>
      <tr>
        <td><div class="form-group">
            {{Form::label('receiptno', 'Receipt Number')}}
            {{Form::text('receiptno', '', ['class' => 'form-control', 'placeholder' => 'Receipt Number'])}}
        </div></td>
        <td><div class="form-group">
            {{Form::label('vehregno', 'Vehicle Reg No')}}
            {{Form::text('vehregno', '', ['class' => 'form-control', 'placeholder' => 'Vehicle Reg No'])}}
        </div></td>
        <td><div class="form-group">
            {{Form::label('fueltype', 'Fuel Type')}}
            {{Form::select('fueltype', ['' => '', 'diesel' => 'Diesel', 'petrol' => 'Petrol'], '', ['class' => 'form-control', 'placeholder' => 'Fuel Type'])}}
        </div></td>
      </tr>
      <tr>
        <td><div class="form-group">
              {{Form::label('first_date', 'First Date')}}
              {{Form::text('first_date', '', ['class' => 'date form-control', 'placeholder' => 'yyyy-mm-dd'])}}
          </div></td>
        <td><div class="form-group">
            {{Form::label('last_date', 'Last Date')}}
            {{Form::text('last_date', '', ['class' => 'date form-control', 'placeholder' => 'yyyy-mm-dd'])}}
        </div></td>
        <td><div class="form-group">
            {{Form::label('paymethod', 'Payment Method')}}
            {{Form::select('paymethod', ['' => '', 'Cash' => 'Cash', 'Credit' => 'Credit', 'MPesa' => 'MPesa'], '', ['class' => 'form-control', 'placeholder' => 'Payment Method'])}}
        </div></td>
      </tr>
      <tr>
        <td><div class="form-group">
              {{Form::label('attendantid', 'Attendant\'s Name')}}
              {{Form::select('attendantid', ['' => ''] + $attendants, '', ['class' => 'form-control'])}}
          </div></td>
        <td>
            @if(Auth::user()->usertype == 'admin')
            <div class="form-group">
              {{Form::label('stationsel', 'Station Name')}}
              {{Form::select('stationsel', ['' => ''] + $stations, '', ['class' => 'form-control'])}}
            </div>
            @endif
        </td>
        <td></td>
      </tr>
      </tbody>
    </table>
    {{Form::submit('Submit', ['class'=>'btn btn-primary', 'name' => 'submitBtn'])}}
    {{Form::submit('CreatePDF', ['class'=>'btn btn-primary', 'name' => 'submitBtn', 'formtarget' => '_blank'])}}
    <hr>
</div>
<div class="row">

        
    @if(count($txns) > 0)
        <?php
            $colcount = count($txns);
            $i = 1;
        ?>
         
        <table class="table table-striped" >
        <tr>
        <th>Receipt No</th>
        <th>Vehicle Reg No</th>
        <th>Amount (KShs)</th>
        <th>Volume (l)</th>
        <th>Rate</th>
        <th>Fuel Type</th>
        <th>Payment Method</th>
        <th>Txn Date/Time</th>
        <th>User ID</th>
        <th>Station ID</th>
        <th></th>
        </tr>
        @foreach($txns as $txn)
        <tr>
        
        <td>{{$txn['receiptno']}}</td>
        <td>{{$txn['vehregno']}}</td>
        <td>{{$txn['amount']}}</td>
        <td>{{$txn['volume']}}</td>
        <td>{{$txn['sellprice']}}</td>
        <td>{{$txn['fueltype']}}</td>
        <td>{{$txn['paymethod']}}</td>
        <td>{{$txn['created_at']}}</td>
        <td>{{$txn['user']['fullname']}}</td>
        <td>{{$txn['station']['station']}}</td>
        <td><a class="btn btn-default" href="/txns/{{$txn->id}}/edit">Edit</a></td>

        </tr>
        @endforeach
        </table>
        
        
    @else
      <p>No txns To Display</p>
    @endif
</div>
@endsection