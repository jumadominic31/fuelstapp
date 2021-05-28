@extends('layouts.app')

@section('content')
<h1>Members Fueling Report</h1>
<p>Default - Current Month</p>
<a class="pull-right btn btn-default" href="{{ route('loyalty.members') }}">Reset</a>
<h4> Filter </h4>
<input type="checkbox" autocomplete="off" onchange="checkfilter(this.checked);"/>
<div id="filteroptions" style="display: none ;">
    {!! Form::open(['action' => 'TxnsController@memloyaltySummary', 'method' => 'GET', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('owner_id', 'Owner Name')}}
            {{Form::select('owner_id', ['' => ''] + $owners, null, ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('month', 'Month')}}
            {{Form::text('month', '', ['class' => 'form-control month', 'placeholder' => 'Choose Month yyyy-mm'])}}
        </div>
    {{Form::submit('Submit', ['class'=>'btn btn-primary', 'name' => 'submitBtn'])}}
    {{Form::submit('CreatePDF', ['class'=>'btn btn-primary', 'name' => 'submitBtn', 'formtarget' => '_blank'])}}
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
        <th>Ownerid</th>
        <th>Total Volume</th>
        <th></th>
        </tr>

        @foreach($txns as $txn)
        <tr>
        
        <td>{{$txn['owner']['fullname']}}</td>
        <td>{{$txn['total_vol']}}</td>
        <td><a class="pull-right btn btn-sm btn-default" href="{{ route('loyalty.memshow', ['ownerid' => $txn->ownerid ]) }}">Details</a></td>

        </tr>
        @endforeach

        </table>
        {{ $txns->appends(request()->input())->links() }}
        
    @else
      <p>No txns To Display</p>
    @endif
</div>
@endsection