@extends('layouts.app')

@section('content')
<a href="{{ route('eodays.new.create') }}" class="btn btn-default">Go Back</a>
<h1> EOD Entry</h1>
{!! Form::open([ 'action' => 'EodaysController@posteodentry', 'method' => 'POST', 'onsubmit' => 'return confirm("Are you sure?")', 'enctype' => 'multipart/form-data']) !!}
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
            @foreach($attendants as $key => $att)
            <tr>
                <td style="display:none;">{{Form::text('attid_'.$key, $key , ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('attname_'.$key, $att , ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('attcash_'.$key , '0' , ['class' => 'form-control'])}}</td>
                <td>{{Form::text('attmpesa_'.$key , '0' , ['class' => 'form-control'])}}</td>
                <td>{{Form::text('attcredit_'.$key , '0' , ['class' => 'form-control'])}}</td>
                <td>{{Form::text('attvisa_'.$key , '0' , ['class' => 'form-control'])}}</td>
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
                <td style="display:none;">{{Form::text('itemid_'.$product['id'], $product['id'] , ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('itemname_'.$product['id'], $product['name'], ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('itemcash_'.$product['id'] , '0' , ['class' => 'form-control'])}}</td>
                <td>{{Form::text('itemmpesa_'.$product['id'] , '0' , ['class' => 'form-control'])}}</td>
                <td>{{Form::text('itemcredit_'.$product['id'] , '0' , ['class' => 'form-control'])}}</td>
                <td>{{Form::text('itemvisa_'.$product['id'] , '0' , ['class' => 'form-control'])}}</td>
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
                <td style="display:none;">{{Form::text('pumpid_'.$pump['id'], $pump['id'] , ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('pumpname_'.$pump['id'], $pump['pumpname'] , ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('pumpprev_'.$pump['id'], $pump['reading'] , ['class' => 'form-control'])}}</td>
                <td>{{Form::text('pumpret_'.$pump['id'], '0' , ['class' => 'form-control'])}}</td>
                <td>{{Form::text('pumpnew_'.$pump['id'], '' , ['class' => 'form-control'])}}</td>
                <td>{{Form::select('pumpatt_'.$pump['id'],  ['' => ''] + $attendants, '' , ['class' => 'form-control'])}}</td>
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
                <td style="display:none;">{{Form::text('tankid_'.$tank['id'], $tank['id'] , ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('tankname_'.$tank['id'], $tank['tankname'] , ['class' => 'form-control', 'readonly' => 'true'])}}</td>
                <td>{{Form::text('tankprev_'.$tank['id'], $tank['reading'] , ['class' => 'form-control'])}}</td>
                <td>{{Form::text('tankpurc_'.$tank['id'], '0' , ['class' => 'form-control'])}}</td>
                <td>{{Form::text('tanknew_'.$tank['id'], '' , ['class' => 'form-control'])}}</td>
            </tr>
            @endforeach
        </table>
    </div>
        
    {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
{!! Form::close() !!}


@endsection