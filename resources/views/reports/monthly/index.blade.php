@extends('layouts.app')

@section('content')
<h1> Monthly Report </h1>
<a class="btn btn-default" href="{{ route('eodays.downloadeodayExcel', ['type' => 'xlsx' ]) }}">Download All Eoday</a> 
<a class="pull-right btn btn-default" href="{{ route('monthly.get') }}">Reset</a>
<h3> Filter </h3>
<div class="row">
<input type="checkbox" autocomplete="off" onchange="checkfilter(this.checked);"/>
<div id="filteroptions" style="display: none ;">
    <table class="table">
        <tbody>
            <tr>
                {!! Form::open(['action' => 'EodaysController@monthlyrpt', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
                        @if (Auth::user()->usertype == 'admin')
                        <td><div class="form-group">
                            {{Form::label('station', 'Station')}}
                            {{Form::select('station', $stations, null, ['class' => 'form-control'])}}
                        </div></td>
                        @endif
                        <td><div class="form-group">
                            {{Form::label('fueltype', 'Fuel Type')}}
                            {{Form::select('fueltype', ['Diesel' => 'Diesel', 'Petrol' => 'Petrol'], null, ['class' => 'form-control', 'placeholder' => 'Choose fuel type'])}}
                        </div></td>
                        <td><div class="form-group">
                            {{Form::label('month', 'Month')}}
                            {{Form::text('month', '', ['class' => 'form-control month', 'placeholder' => 'Choose Month yyyy-mm'])}}
                        </div></td>
                        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
                {!! Form::close() !!}
            </tr>
        </tbody>
    </table>
</div>
<hr>
    <table class="table table-bordered">
        <thead class="thead-default">
        <tr>
            <th>Date</th>
            <th>Station</th>
            <th>Fuel Type</th>
            <th>Open stock</th>
            <th>Purchases</th>
            <th>Sales</th>
            <th>Close stock</th>
            <th>Sell Price</th>
            <th>Gross Sales</th>
            <th>Banked</th>
            <th>MPesa</th>
            <th>Credit</th>
            <th>Expenses</th>
            <th>Total Coll</th>
            <th>Difference</th>
        </tr>
        </thead>
        <?php 
            $sum_purchases = 0;
            $sum_tot_vol = 0;  
            $sum_tot_val = 0;
            $sum_tot_coll = 0;
            $sum_shortage = 0;
            $sum_tot_banked = 0;  
            $sum_tot_mpesa = 0;
            $sum_tot_credit = 0;
            $sum_tot_expenses = 0;
        ?>
        @foreach($monthlyrpts as $monthlyrpt)
        <tr>
            <td>{{$monthlyrpt['created_at']}}</td>
            <td>{{$monthlyrpt['station']['station']}}</td>
            <td>{{$monthlyrpt['fueltype']}}</td>
            <td>{{$monthlyrpt['open_stock']}}</td>
            <td>{{$monthlyrpt['purchases']}}</td>
            <td>{{$monthlyrpt['tot_vol']}}</td>
            <td>{{$monthlyrpt['close_stock']}}</td>
            <td>{{$monthlyrpt['rate']}}</td>
            <td>{{$monthlyrpt['tot_val']}}</td>
            <td>{{$monthlyrpt['banked']}}</td>
            <td>{{$monthlyrpt['mpesa']}}</td>
            <td>{{$monthlyrpt['credit']}}</td>
            <td>{{$monthlyrpt['expenses']}}</td>
            <td>{{$monthlyrpt['tot_coll']}}</td>
            <td>{{$monthlyrpt['shortage']}}</td>

            <?php
                $sum_purchases += $monthlyrpt['purchases'];
                $sum_tot_vol += $monthlyrpt['tot_vol'];  
                $sum_tot_val += $monthlyrpt['tot_val'];
                $sum_tot_coll += $monthlyrpt['tot_coll'];
                $sum_shortage += $monthlyrpt['shortage'];
                $sum_tot_banked += $monthlyrpt['banked'];  
                $sum_tot_mpesa += $monthlyrpt['mpesa'];
                $sum_tot_credit += $monthlyrpt['credit'];
                $sum_tot_expenses += $monthlyrpt['expenses'];
             ?>
        </tr>
        @endforeach
        <thead class="thead-default">
        <tr>
            <th>Total</th>
            <th></th>
            <th></th>
            <th></th>
            <th>{{$sum_purchases}}</th>
            <th>{{$sum_tot_vol}}</th>
            <th></th>
            <th></th>
            <th>{{$sum_tot_val}}</th>
            <th>{{$sum_tot_banked}}</th>
            <th>{{$sum_tot_mpesa}}</th>
            <th>{{$sum_tot_credit}}</th>
            <th>{{$sum_tot_expenses}}</th>
            <th>{{$sum_tot_coll}}</th>
            <th>{{$sum_shortage}}</th>
            
        </tr>
        </thead>
    
    </table>
</div>
@endsection