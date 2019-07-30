@extends('superadmin.app')

@section('content')
<div class="container">
@include('superadmin.messages')
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">Dashboard</div>
    </div>
    
    </div>
    <!-- Website Overview -->
    <div class="panel panel-default">
        <div class="panel-heading main-color-bg">
            <h3 class="panel-title">Overview</h3>
        </div>
        <div class="panel-body">
            <div class="col-md-3">
                <div class="well dash-box">
                    <h2><span class="glyphicon glyphicon-home" aria-hidden="true"></span> {{$companies_cnt}}</h2>
                    <h4>Companies</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="well dash-box">
                    <h2><span class="glyphicon glyphicon-dashboard" aria-hidden="true"></span> {{$stations_cnt}}</h2>
                    <h4>Stations</h4>
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
                    <h2><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{$users_cnt}}</h2>
                    <h4>Attendants</h4>
                </div>
            </div>
        </div>
    </div>

<!-- Latest Summaries -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Latest Sales</h3>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-hover">
                <tr>
                <th>Company Name</th>
                <th>Company Status</th>
                </tr>
                @foreach ($companies as $company)
                <tr>
                <td>{{$company['name']}}</td>
                <td>{{$company['status']}}</td>
                </tr>
                @endforeach
                
            </table>
        </div>
    </div>
    

</div>
@endsection
