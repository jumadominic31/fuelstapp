@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                Edit Transaction Details<br> 
            </div>
            <div class="panel-body">
                <a href="{{ route('txns.index') }}" class="btn btn-default btn-xs">Go Back</a>
                {!! Form::open(['action' => ['TxnsController@cancel', $txn->id ], 'method' => 'POST']) !!}
                    {{Form::hidden('_method', 'PUT')}}
                    {{Form::submit('Cancel', ['class'=>'btn btn-danger btn-xs pull-right', 'name' => 'CancelBtn','onsubmit' => 'return confirm("Are you sure you want to cancel?")'])}}
                {!! Form::close() !!}
            </div>
            <div class="panel-body">
              {!!Form::open(['action' => ['TxnsController@update', $txn->id],'method' => 'POST'])!!}
                <div class="form-group">
                    {{Form::label('receiptno', 'Receipt Number')}}
                    {{Form::text('receiptno', $txn->receiptno, ['class' => 'form-control', 'readonly' => 'true'])}}
                </div>
                @if ($txn->cancelled == '1')
                <div class="form-group">
                    {{Form::label('cancelled', 'Cancelled')}}
                    {{Form::text('cancelled', 'Yes', ['class' => 'form-control', 'readonly' => 'true'])}}
                </div>
                @endif
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
                    {{Form::text('sellprice', $txn->sellprice, ['class' => 'form-control', 'readonly' => 'true'])}}
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
                @if ($txn->cancelled == '1')
                    {{Form::submit('Submit', ['disabled' => 'true'])}}
                @else
                    {{Form::submit('Submit')}}
                @endif
              {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection