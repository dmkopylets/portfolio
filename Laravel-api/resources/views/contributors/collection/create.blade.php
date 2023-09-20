@extends('layouts.app')
@section('content')

    <div class="relative flex items-top justify-center min-h-screen  sm:items-center sm:pt-0">
        <div class="container jumbotron">
            <nav class="navbar navbar-inverse">
                <div class="navbar-header">
                    <a class="navbar-brand">Сreate a new record</a>
                </div>
                <ul class="nav navbar-nav">
                    <li><a href="{{ URL::to('collections') }}">Return to the list collections</a></li>
                </ul>
            </nav>

            <!-- Form::open(array('url' => URL::to('collections'), 'method' => 'POST')) -->
            {{ Form::model($modelName, array('route' => array('collections.store', $record), 'method' => 'POST')) }}
            <table class="table"  id="dict-table">
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
                        {{ Form::text($add_td[$i], '', array('class' => 'form-control')) }}
                    </td>
                @endforeach
            </tr>
            </table>
            {{ Form::submit('Save changes', array('class' => 'btn btn-primary')) }}
            {{ Form::close() }}

        </div>
    </div>
