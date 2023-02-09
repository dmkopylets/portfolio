@extends('layouts.app')
@section('content')
<div class="relative flex items-top justify-center min-h-screen  sm:items-center sm:pt-0"> <!-- центоровка по вертикалі -->
    <div class="relative flex items-top justify-center min-h-screen  sm:items-center sm:pt-0">
        <div class="container jumbotron">
            <h2><strong>Оберіть специфіку робіт</strong></h2>
        <div class="content">


<form action="{{ url('orders/create') }}" method="GET">
@csrf
<div class="direction-task">
    <hr class="mb-8">
    <div class=".col-md-4 .col-md-4">
        <div class="row">
            @foreach($workspecs as $workspec)
                &nbsp
                <div class="form-check form-check-inline .col-md-1">
                    <input class="form-check-input works_specs_id" type="radio" name="direction" value="{{$workspec->id}}")" >
                    <label class="form-check-label" for="inlineRadio{{$workspec->id}}">{{$workspec->body}}</label>
                </div>
            @endforeach
        </div>
    </div>
    <hr class="mb-8">
</div>
{{ Form::submit('Далі', array('class' => 'btn btn-primary','style' => 'float: right; margin-right: 10px;')) }}
{{ Form::close() }}
</div>
</div>
</div>
</div>

<script type="text/javascript" src="{{asset('js/jquery.min.js')}}" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js')}}" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
@endsection


