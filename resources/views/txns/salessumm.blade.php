@extends('layouts.app')

@section('content')
<h1> Summary per Attendant </h1>

<a class="pull-right btn btn-default" href="{{ route('txns.salessumm.index') }}">Reset</a>
<h3> Filter </h3>
<input type="checkbox" autocomplete="off" onchange="checkfilter(this.checked);"/>
<div id="filteroptions" style="display: none ;">
    {!! Form::open(['action' => 'TxnsController@salessumm', 'method' => 'GET', 'enctype' => 'multipart/form-data']) !!}
        <table class="table">
            <tbody>
                <tr>
                    @if (Auth::user()->usertype == 'admin')
                    <td>
                        <div class="form-group">
                            {{Form::label('station', 'Station')}}
                            {{Form::select('station', ['' => ''] + $stations, null, ['class' => 'form-control', 'optional' => 'Choose Station'])}}
                        </div>
                    </td>
                    @endif
                    <td>
                        <div class="form-group">
                            {{Form::label('fueltype', 'Fuel Type')}}
                            {{Form::select('fueltype', ['' => '', 'diesel' => 'Diesel', 'petrol' => 'Petrol', 'kerosene' => 'Kerosene'], null, ['class' => 'form-control'])}}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            {{Form::label('summ_date_1', 'First Date')}}
                            {{Form::text('summ_date_1', '', ['class' => 'form-control date', 'placeholder' => 'Choose Date yyyy-mm-dd'])}}
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            {{Form::label('summ_date_2', 'Last Date (Choose First Date to use this)')}}
                            {{Form::text('summ_date_2', '', ['class' => 'form-control date', 'placeholder' => 'Choose Date yyyy-mm-dd'])}}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
</div>
<hr>
<div class="row">

        
    @if(count($txns) > 0)
        <?php
            $colcount = count($txns);
            $i = 1;
        ?>
         
        <table class="table table-striped" >
        <tr>
        <th>Attendant</th>
        <th align="center">Total Volume</th>
        <th align="center">Total Sales</th>
        </tr>
        @foreach($txns as $txn)
        <tr>
        
        <td>{{$txn['user']['username']}}</td>
        <td align="right">{{number_format($txn['total_vol'], 2, '.', ',')}}</td>
        <td align="right">{{number_format($txn['total_sales'], 2, '.', ',')}}</td>
        </tr>
        @endforeach

        </table>
        
    @else
      <p>No Sales on the selected dates</p>
    @endif
</div>
@endsection