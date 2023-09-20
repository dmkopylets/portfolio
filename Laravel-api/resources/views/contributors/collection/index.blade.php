@extends('layouts.app')
@section('content')

<div class="container">
    <h2><strong>Collections list</strong></h2>
    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif

    <div class="flex-center position-ref full-height">
       <form class="GridWithSearch">
           @csrf
            <table class="table table-fixed table-striped" id="dict-table">
              <thead>
                <tr>
                    <th width="55px">#</th>
                   @foreach ($add_th as  $i => $value)
                   <th class="col-xs-2" width="{{$th_width[$i]}}px">
                       <input type="text" name="wanted_{{$add_td[$i]}}" id="wanted_{{$add_td[$i]}}" class="form-control"
                        value="<?php echo isset($_REQUEST['wanted_'.$add_td[$i]])?$_REQUEST['wanted_'.$add_td[$i]]:''?>"
                        placeholder={{$add_th[$i]}}>
                   </th>
                   @endforeach
                    <th class="col-xs-2" width="275px">
                          <div style="float: right; margin-right: 5px;">
                            <button href="#" type="submit" name="search" value="search" id="search" class="btn btn-small btn-outline-primary" >  <i class="fa fa-fw fa-search"></i> Find</button>
                            <a href="{{asset('/').'collections/create'}}" class="btn btn-small btn-outline-info" ><i class="fa fa-plus" aria-hidden="true"></i> New collection</a>
                        </div>
                    </th>
                </tr>
              </thead>
              <tbody>
                @foreach($records as $record)
                    <tr>
                        <th  width="55px">{{$record['id']}} </th>
                            @foreach ($add_td as $i => $value)
                                <td  width="{{$th_width[$i]}}px">{{$record[$value]}} </td>
                            @endforeach
                       <td class="col-xs-2">
                        <div style="float: right; margin-right: 2px;">
                           <a href="{{ asset('/') . 'collections/' . $record['id'] . '/edit' }}" class="btn btn-outline-info btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> edit</a>&nbsp&nbsp
                           <a href="{{ asset('/') . 'collections/' . $record['id'] . '/delete' }}" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash-o fa-lg" aria-hidden="true"></i> delete</a>
                        </div>
                       </td>
                    </tr>
                @endforeach
            </tbody>
         </table>
   </form>
</div>
</div>
@endsection
