@extends('layouts.adminLayout')
@section('title',"list don't existing files:")
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">extension is not possible</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <h1 class="m-0">there is a problem with these files:</h1>
            <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
                <table id="report">
                    <tbody>
                    @foreach ($files as $fileName)
                        <tr>
                            <td>{{ $fileName }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <h1 class="m-0">they are absent</h1>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
