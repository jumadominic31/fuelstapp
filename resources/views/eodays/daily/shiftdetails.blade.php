<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>Station Name</th>
        <td>{{$shift['station']['station']}}</td>
    </tr>
    <tr>
        <th>Shift Date</th>
        <td>{{$shift['date']}}</td>
    </tr>
    <tr>
        <th>Shift No</th>
        <td>{{$shift['shift']}}</td>
    </tr>
</table>

<div>
    <h2>Pump Sales </h2>
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>Pump Name</th>
            <th>Fuel Type</th>
            <th>Opening Reading</th>
            <th>Returned</th>
            <th>Closing Reading</th>
            <th>Vol Sold</th>
            <th>Unit Price</th>
            <th>Cash Sold</th>
            <th>Attendant</th>
        </tr>
        <tr>
            <th colspan="9" style="text-align: center">Diesel</th>
        </tr>
        @foreach($pumpshift as $pump)
        @if($pump['fuel_type'] == 'Diesel')
        <tr>
            <td>{{$pump['pump']['pumpname']}}</td>
            <td>{{$pump['fuel_type']}}</td>
            <td style="text-align: right;">{{number_format($pump['opening'], 2)}}</td>
            <td style="text-align: right;">{{number_format($pump['returned'], 2)}}</td>
            <td style="text-align: right;">{{number_format($pump['closing'], 2)}}</td>
            <td style="text-align: right;">{{number_format($pump['sales'], 2)}}</td>
            <td style="text-align: right;">{{number_format($pump['unitprice'], 2)}}</td>
            <td style="text-align: right;">{{number_format($pump['total'], 2)}}</td>
            <td>{{$pump['attendant']['fullname']}}</td>
        </tr>
        @endif
        @endforeach
        <tr>
            <th colspan="5">Diesel Tot</th>
            <th style="text-align: right;">{{number_format($pumptots['diesel_vol'], 2)}}</th>
            <th></th>
            <th style="text-align: right;">{{number_format($pumptots['diesel_sales'], 2)}}</th>
            <th></th>
        </tr>
        <tr>
            <th colspan="9" style="text-align: center">Petrol</th>
        </tr>
        @foreach($pumpshift as $pump)
        @if($pump['fuel_type'] == 'Petrol')
        <tr>
            <td>{{$pump['pump']['pumpname']}}</td>
            <td>{{$pump['fuel_type']}}</td>
            <td style="text-align: right;">{{number_format($pump['opening'], 2)}}</td>
            <td style="text-align: right;">{{number_format($pump['returned'], 2)}}</td>
            <td style="text-align: right;">{{number_format($pump['closing'], 2)}}</td>
            <td style="text-align: right;">{{number_format($pump['sales'], 2)}}</td>
            <td style="text-align: right;">{{number_format($pump['unitprice'], 2)}}</td>
            <td style="text-align: right;">{{number_format($pump['total'], 2)}}</td>
            <td>{{$pump['attendant']['fullname']}}</td>
        </tr>
        @endif
        @endforeach
        <tr>
            <th colspan="5" >Petrol Tot</th>
            <th style="text-align: right;">{{number_format($pumptots['petrol_vol'], 2)}}</th>
            <th></th>
            <th style="text-align: right;">{{number_format($pumptots['petrol_sales'], 2)}}</th>
            <th></th>
        </tr>
        <tr>
            <th colspan="9" style="text-align: center">Kerosene</th>
        </tr>
        @foreach($pumpshift as $pump)
        @if($pump['fuel_type'] == 'Kerosene')
        <tr>
            <td>{{$pump['pump']['pumpname']}}</td>
            <td>{{$pump['fuel_type']}}</td>
            <td style="text-align: right;">{{number_format($pump['opening'], 2)}}</td>
            <td style="text-align: right;">{{number_format($pump['returned'], 2)}}</td>
            <td style="text-align: right;">{{number_format($pump['closing'], 2)}}</td>
            <td style="text-align: right;">{{number_format($pump['sales'], 2)}}</td>
            <td style="text-align: right;">{{number_format($pump['unitprice'], 2)}}</td>
            <td style="text-align: right;">{{number_format($pump['total'], 2)}}</td>
            <td>{{$pump['attendant']['fullname']}}</td>
        </tr>
        @endif
        @endforeach
        <tr>
            <th colspan="5" >Kerosene Tot</th>
            <th style="text-align: right;">{{number_format($pumptots['kerosene_vol'], 2)}}</th>
            <th></th>
            <th style="text-align: right;">{{number_format($pumptots['kerosene_sales'], 2)}}</th>
            <th></th>
        </tr>
        <tr>
            <th colspan="5" >Combined Tot</th>
            <th style="text-align: right;">{{number_format($pumptots['total_vol'], 2)}}</th>
            <th></th>
            <th style="text-align: right;">{{number_format($pumptots['total_sales'], 2)}}</th>
            <th></th>
        </tr>
    </table>
</div>

<div>
    <h2>Summary of Sales per Attendant</h2>
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>Attendant</th>
            <th>Total Sales</th>
        </tr>
        @foreach ($pumpatt as $patt)
        <tr>
            <td>{{$patt['attendant']['fullname']}}</td>
            <td style="text-align: right;">{{number_format($patt['tot_sales'], 2)}}</td>
        </tr>
        @endforeach
        <tr>
            <th>Total</th>
            <th style="text-align: right;">{{number_format($pumptots['total_sales'], 2)}}</th>
        </tr>
    </table>
</div>

<div>
    <h2>Actual Collection</h2>
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>Attendant ID</th>
            <th>Cash</th>
            <th>MPesa</th>
            <th>Credit</th>
            <th>Visa</th>
            <th>Total</th>
        </tr>
        @foreach($actcoll as $coll)
        <tr>
            <td>{{$coll['attendant']['fullname']}}</td>
            <td style="text-align: right;">{{number_format($coll['cash'], 2)}}</td>
            <td style="text-align: right;">{{number_format($coll['mpesa'], 2)}}</td>
            <td style="text-align: right;">{{number_format($coll['credit'], 2)}}</td>
            <td style="text-align: right;">{{number_format($coll['visa'], 2)}}</td>
            <td style="text-align: right;">{{number_format($coll['total'], 2)}}</td>
        </tr>
        @endforeach
        <tr>
            <th>Totals</th>
            <th style="text-align: right;">{{number_format($actsumm['tot_cash'], 2)}}</th>
            <th style="text-align: right;">{{number_format($actsumm['tot_mpesa'], 2)}}</th>
            <th style="text-align: right;">{{number_format($actsumm['tot_credit'], 2)}}</th>
            <th style="text-align: right;">{{number_format($actsumm['tot_visa'], 2)}}</th>
            <th style="text-align: right;">{{number_format($actsumm['tot_total'], 2)}}</th>
        </tr>
    </table>
</div>

<div>
    <h2>Attendant Shortages</h2>
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>Attendant</th>
            <th>Shortage Amount</th>
        </tr>
        @foreach ($shortage as $short)
        <tr>
            <td>{{$short['attendant_name']}}</td>
            <td style="text-align: right;">{{number_format($short['amount'], 2)}}</td>
        </tr>
        @endforeach
        <tr>
            <th>Total</th>
            <th style="text-align: right;">{{number_format($tot_short, 2)}}</th>
        </tr>
    </table>
</div>

{{-- <div>
    {{$poscoll}}
    <h2>**UPDATE -- Collection POS - To get data from the txns table</h2>
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>Attendant ID</th>
            <th>Cash</th>
            <th>MPesa</th>
            <th>Credit</th>
            <th>Visa</th>
            <th>Total</th>
        </tr>
        @foreach($poscoll as $pcoll)
            <tr>
                <td>{{$pcoll['user']['fullname']}}</td>
                <td>
                    @if ($pcoll['paymethod'] == 'Cash')
                        {{number_format($pcoll['tot_amount'], 2)}}
                    @else
                        {{number_format(0, 2)}}
                    @endif
                </td>
                <td>
                    @if ($pcoll['paymethod'] == 'MPesa')
                        {{number_format($pcoll['tot_amount'], 2)}}
                    @else
                    {{number_format(0, 2)}}
                    @endif
                </td>
                <td>0</td>
                <td>0</td>
                <td>0</td>
            </tr>
        @endforeach
        <tr>
            <th>Total</th>
            <th>Tot Cash</th>
            <th>Tot Mpesa</th>
            <th>Tot Credit</th>
            <th>Tot Visa</th>
            <th>Total</th>
        </tr>
    </table>
</div> --}}

<div>
    <h2>Tank Reading (Volumes)</h2>
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>Tank Name</th>
            <th>Fuel Type</th>
            <th>Opening Reading</th>
            <th>Purchased</th>
            <th>Closing Reading</th>
            <th>Vol Sold</th>
        </tr>
        @foreach($tankshift as $tank)
        <tr>
            <td>{{$tank['tank']['tankname']}}</td>
            <td>{{$tank['fueltype']}}</td>
            <td style="text-align: right;">{{number_format($tank['opening'], 2)}}</td>
            <td style="text-align: right;">{{number_format($tank['purchased'], 2)}}</td>
            <td style="text-align: right;">{{number_format($tank['closing'], 2)}}</td>
            <td style="text-align: right;">{{number_format($tank['sold'], 2)}}</td>
        </tr>
        @endforeach
        <tr>
            <th colspan="3">Total</th>
            <th style="text-align: right;">{{number_format($tanksumm['tot_purchased'], 2)}}</th>
            <th></th>
            <th style="text-align: right;">{{number_format($tanksumm['tot_sold'], 2)}}</th>
        </tr>
    </table>
</div>