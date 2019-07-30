@extends('layouts.app')

@section('content')
<div class="container">
@include('inc.messages')
<div class="row">
    <!--<div class="panel panel-default">
        <div class="panel-heading">Dashboard</div>
    </div>-->
    <section id="breadcrumb">
        <div class="container">
            <ol class="breadcrumb">
            <li class="active">Dashboard</li>
            </ol>
        </div>
    </section>


    </div>
    <!-- Website Overview -->
    <div class="panel panel-default">
        <div class="panel-heading main-color-bg">
            <h3 class="panel-title">Overview</h3>
        </div>
        <div class="panel-body">
            <div class="col-md-3">
                <div class="well dash-box">
                    <h2><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> {{$station['station']['station']}}</h2>
                    <h4>Station Name</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="well dash-box">
                    <h2><span class="glyphicon glyphicon-usd" aria-hidden="true"></span> {{$total_sales}}</h2>
                    <h4>Total Day's Collection</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="well dash-box">
                    <h2><span class="glyphicon glyphicon-oil" aria-hidden="true"></span> {{$pumps_cnt}}</h2>
                    <h4>Pumps</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="well dash-box">
                    <h2><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{$attendants_cnt}}</h2>
                    <h4>Attendants</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Sales -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Latest Sales</h3>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-hover">
                <tr>
                <th>Attendant ID</th>
                <th>Total Sales</th>
                </tr>
                @foreach ($attendant_sales as $attendant_sale)
                <tr>
                <td>{{$attendant_sale['user']['username']}}</td>
                <td>{{$attendant_sale['total_sales']}}</td>
                </tr>
                @endforeach
                
            </table>
        </div>
    </div>

</div>
@endsection
