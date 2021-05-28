@extends('layouts.app')

@section('content')
    
<h1>Add Collection</h1>
<a href="{{ route('collections.index') }}" class="pull-right btn btn-default">Go Back</a>
<br>
{!! Form::open(['action' => 'CollectionsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
    <div class="form-group">
        {{Form::label('date', 'Collection Date')}}
        {{Form::text('date', $curr_date, ['class' => 'date form-control', 'placeholder' => 'yyyy-mm-dd'])}}
    </div>
    <div class="form-group">
        {{Form::label('owner_id', 'Choose Owner')}}
        {{Form::select('owner_id', ['' => ''] + $owners, '', ['class' => 'form-control', 'id' => 'owner_id'])}}
    </div>
    <div class="form-group">
        {{Form::label('veh_id', 'Vehicle Reg No')}}
        {{Form::select('veh_id', ['' => ''] , null, ['class' => 'form-control', 'id' => 'veh_id'])}}
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
    $('#owner_id').on('change', '', function(e){
        var owner_id = e.target.value;
        $.get('/getcreditownbal/'+owner_id, function(data){
            // console.log(data.vehicles);
            $('#veh_id').empty();
            $('#veh_id').append('<option value=""></option>');
            $('#balance').val(data.balance);
            $.each(data.vehicles, function(index, veh){
                $('#veh_id').append('<option value="'+veh.id+'">'+veh.num_plate+'</option>');
            });
        });
    });
</script>

@endsection