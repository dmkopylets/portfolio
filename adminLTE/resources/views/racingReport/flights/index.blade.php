@extends('layouts.adminLayout')
@section('title','common statistic')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Common statistic</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div
                class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
                <table id="report">
                    <tbody>
                    @foreach ($flights as $key => $flight)
                        <tr>
                            <td>{{ $flight['place']}}</td>
                            <td>{{$flight['driver']}}</td>
                            <td>{{$flight['team']}}</td>
                            <td>{{$flight['duration']}}</td>
                        </tr>
                        @if ($flight['lined'])
                            <tr>
                                <td>--</td>
                                <td>-----------------------------</td>
                                <td>------------------------------</td>
                                <td>---------</td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
