@extends('layouts.app')

@section('content')
<h1>Vehicles Fueling Report </h1>
<h2>Monthly Report</h2>
<a class="pull-right btn btn-default" href="{{ route('reports.vehicles') }}">Reset</a>
<h3> Filter </h3>
<div class="row">
<input type="checkbox" autocomplete="off" onchange="checkfilter(this.checked);"/>
<div id="filteroptions" style="display: none ;">
    <table class="table">
        <tbody>
            <tr>
                {!! Form::open(['action' => 'EodaysController@vehiclesrpt', 'method' => 'GET', 'enctype' => 'multipart/form-data']) !!}
                    <td><div class="form-group">
                            {{Form::label('month', 'Choose Month-Year')}}
                            {{Form::text('month', '', ['class' => 'form-control month', 'placeholder' => 'Choose Month yyyy-mm'])}}
                    </div></td>
                <tr>
                    <td>
                        {{Form::submit('Submit', ['class'=>'btn btn-primary', 'name' => 'submitBtn'])}}
                        {{Form::submit('DownloadExcel', ['class'=>'btn btn-primary', 'name' => 'submitBtn', 'formtarget' => '_blank'])}}
                    </td>
                </tr>
                {!! Form::close() !!}
            </tr>
        </tbody>
    </table>
</div>
<hr>
    <table class="table table-bordered">
        <thead class="thead-default">
            <tr>
            </tr>
        </thead>
        <tr>
            <td>Owner Number</td>
            <td>Owner Name</td>
            <td>Vehicle Reg</td>
            <td>{{$startmondt}}</td>
            @for($i = 1; $i < $daysinmonth; $i++) 
            <?php 
            $startmondt2 = \Carbon\Carbon::parse($startmondt);
            $stdt = $startmondt2->addDays($i);
            $stdtformatted = $stdt->format('Y-m-d');
            ?>
            <td>{{$stdtformatted}}</td>
            @endfor
            <td>Total</td>
        </tr>
        @foreach($vehreport as $vehrpt)
        <tr>
            <td>{{$vehrpt['own_num']}}</td>
            <td>{{$vehrpt['fullname']}}</td>
            <td>{{$vehrpt->vehregno}}</td>
            <td>{{$vehrpt->$startmondt}}</td>
            @for($j = 1; $j < $daysinmonth; $j++)
            <?php 
            $startmondt2 = \Carbon\Carbon::parse($startmondt);
            $stdt = $startmondt2->addDays($j);
            $stdtformatted = $stdt->format('Y-m-d');
            ?>
            <td>{{$vehrpt->$stdtformatted}}</td>
            @endfor
            <td>{{$vehrpt['total']}}</td>
        </tr>
        @endforeach
    
    </table>
</div>
@endsection