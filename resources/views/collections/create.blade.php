@extends('layouts.app')

@section('content')
    
<h1>Add Collection</h1>
<a href="{{ route('collections.index') }}" class="pull-right btn btn-default">Go Back</a>
<br>
{!! Form::open(['action' => 'CollectionsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
    <div class="form-group">
        {{Form::label('veh_id', 'Vehicle Reg No')}}
        {{Form::select('veh_id', ['' => ''] + $vehicles, '', ['class' => 'form-control', 'id' => 'veh_id'])}}
    </div>
    <div class="form-group">
        {{Form::label('owner_id', 'Owner Name')}}
        {{Form::text('owner_id', '', ['class' => 'form-control', 'placeholder' => 'Owner Name', 'disabled' => 'true', 'id' => 'owner_id'])}}
    </div>
    <div class="form-group">
        {{Form::label('balance', 'Balance')}}
        {{Form::text('balance', '', ['class' => 'form-control', 'placeholder' => 'Balance', 'disabled' => 'true', 'id' => 'balance'])}}
    </div>
    <div class="form-group">
        {{Form::label('amount', 'Amount')}}
        {{Form::text('amount', '', ['class' => 'form-control', 'placeholder' => 'Amount'])}}
    </div>
    <div class="form-group">
        {{Form::label('paymethod', 'Payment Method')}}
        {{Form::select('paymethod', ['' => '', 'Cash' => 'Cash', 'Mpesa' => 'Mpesa', 'Visa' => 'Visa'] , '', ['class' => 'form-control'])}}
    </div>
    
    {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
{!! Form::close() !!}

<script>
    $('#veh_id').on('change', '', function(e){
        var veh_id = e.target.value;
        $.get('/getcreditownbal/'+veh_id, function(data){
            $('#owner_id').val(data.owner_name);
            $('#balance').val(data.balance);
        });
    });
</script>

@endsection