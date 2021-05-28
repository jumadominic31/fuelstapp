@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
@endsection

@section('content')
<h1> Transactions </h1>

<a class="pull-right btn btn-default" href="{{ route('txns.index') }}">Reset</a>

<div class="panel panel-default">
  <div class="panel-heading main-color-bg">
      <h3 class="panel-title">Today's Sales</h3>
  </div>
  <div class="panel-body">
      <div class="col-md-3">
          <div class="well dash-box">
              <h2><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> {{number_format($totals['cash'], 2)}}</h2>
              <h4>Cash</h4>
          </div>
      </div>
      <div class="col-md-3">
          <div class="well dash-box">
              <h2><span class="glyphicon glyphicon-usd" aria-hidden="true"></span> {{number_format($totals['mpesa'], 2)}}</h2>
              <h4>Mpesa</h4>
          </div>
      </div>
      <div class="col-md-3">
          <div class="well dash-box">
              <h2><span class="glyphicon glyphicon-oil" aria-hidden="true"></span> {{number_format($totals['credit'], 2)}}</h2>
              <h4>Credit</h4>
          </div>
      </div>
      <div class="col-md-3">
          <div class="well dash-box">
              <h2><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{number_format($totals['visa'], 2)}}</h2>
              <h4>Visa</h4>
          </div>
      </div>
      <div class="col-md-4">
      </div>
      <div class="col-md-4">
        <div class="well dash-box">
        <h2><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{number_format($totals['tot_coll'], 2)}}</h2>
            <h4>Total</h4>
        </div>
    </div>
    <div class="col-md-4">
    </div>
  </div>
</div>
<h3> Filter </h3>
<input type="checkbox" autocomplete="off" onchange="checkfilter(this.checked);"/>
<div id="filteroptions" style="display: none ;">
    {!! Form::open(['action' => 'TxnsController@index', 'method' => 'GET', 'enctype' => 'multipart/form-data']) !!}
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
            {{Form::select('paymethod', ['' => '', 'Cash' => 'Cash', 'Credit' => 'Credit', 'MPesa' => 'MPesa', 'Visa' => 'Visa'], '', ['class' => 'form-control', 'placeholder' => 'Payment Method'])}}
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
         
        <table class="table table-striped" id="txnstable">
            <thead>
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
                    <th>Cancelled</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($txns as $txn)
                <tr>
                
                    <td>{{$txn['receiptno']}}</td>
                    <td>{{$txn['vehregno']}}</td>
                    <td>{{number_format($txn['amount'], 2, '.', ',')}}</td>
                    <td>{{$txn['volume']}}</td>
                    <td>{{number_format($txn['sellprice'], 2, '.', ',')}}</td>
                    <td>{{$txn['fueltype']}}</td>
                    <td>{{$txn['paymethod']}}</td>
                    <td>{{$txn['created_at']}}</td>
                    <td>{{$txn['user']['fullname']}}</td>
                    <td>{{$txn['station']['station']}}</td>
                    <td>{{$txn['cancelled']}}</td>
                    <td><a class="btn btn-default" href="{{ route('txns.edit', ['txn' => $txn->id ]) }}">Edit</a></td>

                </tr>
                @endforeach
            </tbody>
        </table>
        {{-- {{ $txns->appends(request()->input())->links() }} --}}
        
    @else
      <p>No txns To Display</p>
    @endif
</div>
@endsection

@section('javascripts')
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready( function () {
            $('#txnstable').DataTable({
                "searching": false,
                "pageLength": 50
            });
        });
    </script>
@endsection