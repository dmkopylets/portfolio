@extends('layouts.adminLayout')
@section('title','draiver details')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Details about the driver</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
                <!--<p>position: </p> -->
                <p>position: {{$position}}</p>
                <p>name: {{$name}}</p>
                <p>team: {{$team}}</p>
                <p>start: {{$start}}</p>
                <p>finish: {{$finish}}</p>
                <p>result: {{$result}}</p>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
