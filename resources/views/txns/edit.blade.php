@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Edit Transaction Details <a href="{{ route('txns.index') }}" class="pull-right btn btn-default btn-xs">Go Back</a></div>
            <div class="panel-body">
              {!!Form::open(['action' => ['TxnsController@update', $txn->id],'method' => 'POST'])!!}
                <div class="form-group">
                    {{Form::label('receiptno', 'Receipt Number')}}
                    {{Form::text('receiptno', $txn->receiptno, ['class' => 'form-control', 'disabled' => 'disabled'])}}
                </div>
                <div class="form-group">
                    {{Form::label('vehregno', 'Vehicle Reg Number')}}
                    {{Form::text('vehregno', $txn->vehregno, ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('amount', 'Amount')}}
                    {{Form::text('amount', $txn->amount, ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('sellprice', 'Selling Price per litre')}}
                    {{Form::text('sellprice', $txn->sellprice, ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('fueltype', 'Fueltype')}}
                    {{Form::select('fueltype', ['Diesel' => 'diesel', 'Petrol' => 'petrol'], $txn->fueltype, ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('paymethod', 'Payment Method')}}
                    {{Form::select('paymethod', ['Cash' => 'Cash', 'MPesa' => 'MPesa', 'Credit' => 'Credit'], $txn->paymethod, ['class' => 'form-control'])}}
                </div>
                <?php
                    $pumps[0] = 'Select Pump';
                ?>
                <div class="form-group">
                    {{Form::label('pumpid', 'Pump')}}
                    {{Form::select('pumpid', $pumps, null, ['class' => 'form-control'])}}
                </div>
                
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Submit')}}
              {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection