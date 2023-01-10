@extends('layouts.app')
@section('content')

<div class="relative flex items-top justify-center min-h-screen  sm:items-center sm:pt-0">
<div class="container jumbotron">
<nav class="navbar navbar-inverse">
    <div class="navbar-header">
        <a class="navbar-brand" href="{{ URL::to('students') }}">Editing the record # {{ $record->id }} </a>
    </div>
    <ul class="nav navbar-nav">
        <li><a href="{{ URL::to('students') }}">Return to the list students </a></li>
        <li><a href="{{ URL::to('students/create') }}">Сreate a new record</a></li>
    </ul>
</nav>

{{ Form::model($dictName, array('route' => array('students.update', $record->id), 'method' => 'PUT')) }}

<table class="table"  id="editing">
    <thead>
      <tr>
        @foreach ($add_th as  $i => $value)
          <th class="col-xs-2">
            {{ Form::label('th_'.$add_td[$i], $add_th[$i]) }}
          </th>
        @endforeach
        </tr>
    </thead>
    <tr>
        @foreach ($add_td as  $i => $value)
          <td class="col-xs-2">
            {{ Form::label('td_old_'.$add_td[$i], $record->$value) }}
          </td>
        @endforeach
    </tr>
    <tr>
        @foreach ($add_td as  $i => $value)
          <td class="col-xs-2">
            {{ Form::text($add_td[$i], $record->$value, array('class' => 'form-control')) }}
          </td>
        @endforeach
    </tr>
</table>
{{ Form::submit('Save changes', array('class' => 'btn btn-primary')) }}
{{ Form::close() }}

</div>
</div>
