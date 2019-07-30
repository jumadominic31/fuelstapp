@extends('layouts.app')

@section('content')
<h1> Pump Readings</h1>

<div class="row">
    <div class="col-md-8 col-md-offset-0">
        Station
        <div class="container">
        <strong>Enter Pump Readings</strong>

        
            {!! Form::open(['action' => 'ReadingsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'onsubmit' => 'return confirm("Are you sure?")']) !!}
            <table class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                    
                    <th>Pump Name</th>
					<th>Previous Reading</th>
                    <th>New Reading</th>
                </tr>
				@foreach($curr_readings as $curr_reading)
                <tr>
                    
                    <td>{{ $curr_reading->pump->pumpname }}</td>
					<td>{{ $curr_reading->current }}</td>
                    <td>{{Form::text('current_'.$curr_reading->pumpid, '', ['class' => 'form-control', 'placeholder' => 'Current Reading'])}}</td>
                </tr>
				@endforeach
                
            </table>
            <hr>
            <strong> Tank Dip Readings </strong>
            <table class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                    <td>Diesel</td>
                    <td>{{Form::text('diesel_dip', '', ['class' => 'form-control', 'placeholder' => 'Diesel tank dip'])}}</td>
                </tr>
                <tr>
                    <td>Petrol</td>
                    <td>{{Form::text('petrol_dip', '', ['class' => 'form-control', 'placeholder' => 'Petrol tank dip'])}}</td>
                </tr>
            </table>
            <hr>
            <strong> Stock Purchases of the day </strong>
            <table class="table table-striped table-bordered table-hover table-condensed">
                <tr>
                    <td>Diesel</td>
                    <td>{{Form::text('diesel_purchases', '', ['class' => 'form-control', 'placeholder' => 'Diesel purchases litres'])}}</td>
                </tr>
                <tr>
                    <td>Petrol</td>
                    <td>{{Form::text('petrol_purchases', '', ['class' => 'form-control', 'placeholder' => 'Petrol purchases litres'])}}</td>
                </tr>
            </table>
            <hr>
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
                    <td>MPesa</td>
                    <td>{{Form::text('trans_details_3', 'MPesa', ['class' => 'form-control', 'placeholder' => 'Transaction details'])}}</td>
                    <td>{{Form::text('diesel_3', '', ['class' => 'form-control', 'placeholder' => 'Diesel Amount'])}}</td>
                    <td>{{Form::text('petrol_3', '', ['class' => 'form-control', 'placeholder' => 'Petrol Amount'])}}</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Credit</td>
                    <td>{{Form::text('trans_details_4', 'Credit', ['class' => 'form-control', 'placeholder' => 'Transaction details'])}}</td>
                    <td>{{Form::text('diesel_4', '', ['class' => 'form-control', 'placeholder' => 'Diesel Amount'])}}</td>
                    <td>{{Form::text('petrol_4', '', ['class' => 'form-control', 'placeholder' => 'Petrol Amount'])}}</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Expenses</td>
                    <td>{{Form::text('trans_details_5', 'Expenses', ['class' => 'form-control', 'placeholder' => 'Transaction details'])}}</td>
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