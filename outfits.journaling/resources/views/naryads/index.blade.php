@extends('layouts.app')
@section('content')

<div class="container">
    <h2><strong>Електронний Журнал нарядів ({{$branch->body}})</strong></h2>
    <div class="flex-center position-ref full-height">
        <form class="grid_with_search">
            <div class="col-md-12">
                <table class="table  table-fixed table-striped table-bordered" id="orders-table"  style="margin: 10px 0 10px 0;">
                    <thead>
                    <tr>
                        <th scope="col" width="50px">#</th>
                        <th scope="col" width="120px">створено</th>
                        <th scope="col" width="130px"><input type="text" name="searchWarden" id="searchWarden" class="form-control" value="<?php echo isset($_REQUEST['searchWarden'])?$_REQUEST['searchWarden']:''?>" placeholder="керівник"></th>
                        <th scope="col" width="130px"><input type="text" name="searchTerm"   id="searchTerm" class="form-control" value="<?php echo isset($_REQUEST['searchTerm'])?$_REQUEST['searchTerm']:''?>" placeholder="підстанція"></th>
                        <th scope="col">завдання</th>
                        <th scope="col" width="202px">
                        <div>
                            <button href="#" type="submit" name="search" value="search" id="search" class="btn btn-primary"><i class="fa fa-fw fa-search"></i> Пошук</button>
                        </div>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $record)
                        <th scope="row" width="50px">{{$record->id}}</th>
                        <td width="120px">{{$record->order_date}}</td>
                        <td width="130px">{{$record->warden}}</td>
                        <td width="130px">{{$record->substation}}</td>
                        <td>{{$record->tasks}}</td>
                        <td width="190px">
                            <a class="btn btn-danger btn-sm" href="{{asset('/').'naryads/'.$record->id.'/edit'}}"><i class="fa fa-pencil fa-fw"></i>клон</a>
                            &nbsp&nbsp
                            <a class="btn btn-info btn-sm"  href="{{asset('/').'naryads/'.$record->id.'/pdf'}}"><i class="fa fa-eye fa-lg" ></i>&nbsp;друк</a>
                        </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $records->links() }}
        </form>
</div>
</div>

<script type="text/javascript" src="{{asset('js/jquery.min.js')}}" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js')}}" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
@endsection
