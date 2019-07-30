@extends('layouts.app')

@section('content')
<h1> Enter Transactions: </h1> 
<p> bank deposit, cash, expenses, credit, etc. </p>

<div class="row">
    <div class="col-md-8 col-md-offset-0">
        
        <div class="container">
        <strong>Bank Deposit</strong>
        {!! Form::open(['action' => 'OthertxnsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
            <table class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                    <th></th>
                    <th>Transaction Type</th>
                    <th>Transaction Num</th>
                    <th>Diesel Amount</th>
                    <th>Petrol Amount</th>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Banked</td>
                    <td>{{Form::text('trans_details_1', '', ['class' => 'form-control', 'placeholder' => 'Transaction details'])}}</td>
                    <td>{{Form::text('diesel_1', '', ['class' => 'form-control', 'placeholder' => 'Diesel Amount'])}}</td>
                    <td>{{Form::text('petrol_1', '', ['class' => 'form-control', 'placeholder' => 'Petrol Amount'])}}</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Cash</td>
                    <td>{{Form::text('trans_details_2', '', ['class' => 'form-control', 'placeholder' => 'Transaction details'])}}</td>
                    <td>{{Form::text('diesel_2', '', ['class' => 'form-control', 'placeholder' => 'Diesel Amount'])}}</td>
                    <td>{{Form::text('petrol_2', '', ['class' => 'form-control', 'placeholder' => 'Petrol Amount'])}}</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>MPesa</td>
                    <td>{{Form::text('trans_details_3', '', ['class' => 'form-control', 'placeholder' => 'Transaction details'])}}</td>
                    <td>{{Form::text('diesel_3', '', ['class' => 'form-control', 'placeholder' => 'Diesel Amount'])}}</td>
                    <td>{{Form::text('petrol_3', '', ['class' => 'form-control', 'placeholder' => 'Petrol Amount'])}}</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Credit</td>
                    <td>{{Form::text('trans_details_4', '', ['class' => 'form-control', 'placeholder' => 'Transaction details'])}}</td>
                    <td>{{Form::text('diesel_4', '', ['class' => 'form-control', 'placeholder' => 'Diesel Amount'])}}</td>
                    <td>{{Form::text('petrol_4', '', ['class' => 'form-control', 'placeholder' => 'Petrol Amount'])}}</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Expenses</td>
                    <td>{{Form::text('trans_details_5', '', ['class' => 'form-control', 'placeholder' => 'Transaction details'])}}</td>
                    <td>{{Form::text('diesel_5', '', ['class' => 'form-control', 'placeholder' => 'Diesel Amount'])}}</td>
                    <td>{{Form::text('petrol_5', '', ['class' => 'form-control', 'placeholder' => 'Petrol Amount'])}}</td>
                </tr>
            </table>
               
        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
        {!! Form::close() !!}
        
        </div>
    </div>
</div>
@endsection