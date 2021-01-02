@extends('layouts.app')

@section('content')
<a href="{{ route('eodays.daily.index') }}" class="btn btn-default">Go Back</a>
<h1> Edit EOD Entry</h1>
{!! Form::open([ 'action' => ['EodaysController@updateeodentry', $shift_id], 'method' => 'POST', 'onsubmit' => 'return confirm("Are you sure?")', 'enctype' => 'multipart/form-data']) !!}
    <input type="hidden" id="stationid" name="stationid" value="{{$stationid}}">
    <div class="form-group">
        {{Form::label('station', 'Station Name')}}
        {{Form::text('station', $station, ['class' => 'form-control', 'id' => 'station', 'readonly' => 'true'])}}
    </div>
    <div class="form-group">
        {{Form::label('shift_date', 'Shift Date')}}
        {{Form::text('shift_date',  $shift_date, ['class' => 'form-control', 'readonly' => 'true'])}}
    </div>
    <div class="form-group">
        {{Form::label('shift', 'Shift')}}
        {{Form::text('shift',  $shift, ['class' => 'form-control', 'id' => 'shift', 'readonly' => 'true'])}}
    </div>
    <div>
        <h2>Actual Collection by Attendants</h2>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th style="display:none;">Attendant ID</th>
                <th>Attendant</th>
                <th>Cash</th>
                <th>Mpesa</th>
                <th>Credit</th>
                <th>Visa</th>
            </tr>
            @foreach($attcoll as $att)
            <tr>
                <td style="display:none;">{{Form::text('attid_'.$att['attendant_id'], $att['attendant_id'] , ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('attname_'.$att['attendant_id'], $att['attendant']['fullname'] , ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('attcash_'.$att['attendant_id'], $att['cash'] , ['class' => 'form-control'])}}</td>
                <td>{{Form::text('attmpesa_'.$att['attendant_id'], $att['mpesa'] , ['class' => 'form-control'])}}</td>
                <td>{{Form::text('attcredit_'.$att['attendant_id'], $att['credit'] , ['class' => 'form-control'])}}</td>
                <td>{{Form::text('attvisa_'.$att['attendant_id'], $att['visa'] , ['class' => 'form-control'])}}</td>
            </tr>
            @endforeach
        </table> 
    </div>
    
    <div>
        <h2>Other Sales</h2>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th style="display:none;">Product ID</th>
                <th>Product</th>
                <th>Cash</th>
                <th>Mpesa</th>
                <th>Credit</th>
                <th>Visa</th>
            </tr>
            @foreach($products as $product)
            <tr>
                <td style="display:none;">{{Form::text('itemid_'.$product['product_id'], $product['product_id'] , ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('itemname_'.$product['product_id'], $product['product']['name'], ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('itemcash_'.$product['product_id'] , $product['cash'] , ['class' => 'form-control'])}}</td>
                <td>{{Form::text('itemmpesa_'.$product['product_id'] , $product['mpesa'] , ['class' => 'form-control'])}}</td>
                <td>{{Form::text('itemcredit_'.$product['product_id'] , $product['credit'] , ['class' => 'form-control'])}}</td>
                <td>{{Form::text('itemvisa_'.$product['product_id'] , $product['visa'] , ['class' => 'form-control'])}}</td>
            </tr>
            @endforeach
        </table> 
    </div>

    <div>
        <h2>Credit Collection</h2>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th>Vehicle Reg</th>
                <th>Owner Name</th>
                <th>Amount</th>
            </tr>
            @foreach ($creditcoll  as $credit)
            <tr>
                <td>{{Form::text('creditveh_'.$credit['id'], $credit['vehicle']['num_plate'], ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('creditowner_'.$credit['id'], $credit['owner']['fullname'], ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('creditcash_'.$credit['id'] , $credit['amount'] , ['class' => 'form-control', 'readonly' => 'true'])}}</td>
            </tr>
            @endforeach
        </table> 
    </div>

    <div>
        <h2>Pump Readings</h2>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th style="display:none;">Pump ID</th>
                <th>Pump Name</th>
                <th>Previous Reading</th>
                <th>Returned</th>
                <th>New Reading</th>
                <th>Attendant</th>
            </tr>
            @foreach($pumps as $pump)
            <tr>
                <td style="display:none;">{{Form::text('pumpid_'.$pump['pump_id'], $pump['pump_id'] , ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('pumpname_'.$pump['pump_id'], $pump['pump']['pumpname'] , ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('pumpprev_'.$pump['pump_id'], $pump['opening'] , ['class' => 'form-control'])}}</td>
                <td>{{Form::text('pumpret_'.$pump['pump_id'], $pump['returned'] , ['class' => 'form-control'])}}</td>
                <td>{{Form::text('pumpnew_'.$pump['pump_id'], $pump['closing'] , ['class' => 'form-control'])}}</td>
                <td>{{Form::select('pumpatt_'.$pump['pump_id'],  ['' => ''] + $attendants, $pump['attendant_id'] , ['class' => 'form-control'])}}</td>
            </tr>
            @endforeach
        </table>
    </div>

    <div>
        <h2>Tank Readings</h2>
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th style="display:none;">Tank ID</th>
                <th>Tank Name</th>
                <th>Previous Reading</th>
                <th>Purchased</th>
                <th>New Reading</th>
            </tr>
            @foreach ($tanks as $tank)
            <tr>
                <td style="display:none;">{{Form::text('tankid_'.$tank['tank_id'], $tank['tank_id'] , ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('tankname_'.$tank['tank_id'], $tank['tank']['tankname'] , ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('tankprev_'.$tank['tank_id'], $tank['opening'] , ['class' => 'form-control'])}}</td>
                <td>{{Form::text('tankpurc_'.$tank['tank_id'], $tank['purchased'] , ['class' => 'form-control'])}}</td>
                <td>{{Form::text('tanknew_'.$tank['tank_id'], $tank['closing'] , ['class' => 'form-control'])}}</td>
            </tr>
            @endforeach
        </table>
    </div>
    {{Form::hidden('_method', 'PUT')}}
    {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
{!! Form::close() !!}


@endsection