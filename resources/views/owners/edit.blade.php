@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Edit Owner <a href="{{ route('owners.index') }}" class="pull-right btn btn-default btn-xs">Go Back</a></div>

            <div class="panel-body">
                {!!Form::open(['action' => ['OwnersController@update', $owner->id],'method' => 'POST'])!!}
                <div class="form-group">
                    {{Form::label('own_num', 'Owner Number *')}}
                    {{Form::text('own_num', $owner->own_num, ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('fullname', 'Full Name *')}}
                    {{Form::text('fullname', $owner->fullname, ['class' => 'form-control'])}}
                </div>
                <div class="form-group">
                    {{Form::label('phone', 'Phone Number')}}
                    {{Form::text('phone', $owner->phone, ['class' => 'form-control'])}}
                </div>
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Submit')}}
              {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
