@extends('layouts.app')

@section('content')
    <a href="{{ route('eodays.index') }}" class="btn btn-default">Go Back</a>
    <h1>{{$eoday['station']['station']}}</h1>
    <strong>EOD Date/Time: </strong> {{$eoday->created_at}} <br>
    for {{$eoday['fueltype']}}
    <div>
        <hr>
        <strong>Pump Readings</strong>
        <hr>
        <table class="table table-striped">
        <tr><th>Pump ID</th><th>Previous Reading </th><th> Current Reading</th><th> Difference</th></tr>

            @foreach($id_readings as $id_reading)
                     <tr><td>{{ $id_reading->pumpid }}</td><td>{{ $id_reading->previous }} </td><td>  {{ $id_reading->current }} </td><td>  {{$id_reading->diff}} </td></tr>
            @endforeach
        <tr><td></td><td></td><td></td><td><strong>{{$eoday['tot_vol']}}</strong></td></tr>
        </table>
        <hr>
        <strong>Collections</strong>
        <hr>
        <table class="table table-striped">
        <tr><th>Transaction Type </th><th> Transaction details </th><th> Amount</th></tr>

            @foreach($othertxns as $othertxn)
                     <tr><td> {{ $othertxn->txntype }} </td><td> {{ $othertxn->txndetails }} </td><td> {{ $othertxn->amount }}</td></tr>
            @endforeach
        <tr><td></td><td></td><td><strong>{{$eoday['tot_coll']}}</strong></td></tr>
        </table>
        <hr>

        <strong>Collection Summary from POS</strong>
        <hr>
        <table class="table table-striped">
            <tr><th>Transaction Type </th><th> Amount</th></tr>
            <tr><td>Cash</td><td> {{ $eoday['pos_cash']}}</td></tr>
            <tr><td>MPesa</td><td> {{ $eoday['pos_mpesa']}}</td></tr>
            <tr><td>Credit</td><td> {{ $eoday['pos_credit']}}</td></tr>
            <?php 
                $tot_pos_coll = $eoday['pos_cash'] + $eoday['pos_mpesa'] + $eoday['pos_credit'];
            ?>
            <tr><td></td><td><strong>{{$tot_pos_coll}}</strong></td></tr>
        </table>
        <hr>
        <strong>Summary from Pump Reading</strong>
        <hr>
        <table class="table table-striped">
        <tr><td>Total Volume: </td><td>{{$eoday['tot_vol']}} litres </td></tr>
        <tr><td>Diesel Rate: </td><td>{{$eoday['rate']}} KShs/litre </td></tr>
        <tr><td>Total Value: </td><td>KShs. {{$eoday['tot_val']}} </td></tr>
        <tr><td>Total Collection: </td><td>KShs. {{$eoday['tot_coll']}} </td></tr>
        <tr><td>Shortage: </td><td>KShs. {{$eoday['shortage']}} </td></tr>
        </table>
        <hr>

        <strong>Stock (litres)</strong>
        <hr>
        <table class="table table-striped">
        <tr><th></th><th>{{$eoday['fueltype']}}</th></tr>
        <tr><td>Opening Stock</td><td>{{$eoday['open_stock']}}</td></tr>
        <tr><td>Purchases</td><td>{{$eoday['purchases']}} </td></tr>
        <tr><td>Sales</td><td>{{$eoday['tot_vol']}} </td></tr>
        <tr><td>Closing Stock</td><td>{{$eoday['close_stock']}}</td></tr>
        </table>

        <hr>
    </div>
    <hr>
    
@endsection