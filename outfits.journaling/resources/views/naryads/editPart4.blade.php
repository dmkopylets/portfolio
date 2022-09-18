@extends('layouts.app')
@livewireStyles
@section('content')
@include('naryads.edit.f1HidenFields')  <!-- підключаємо приховані поля вводу для обміну з js  -->
<link rel="stylesheet" href="{{asset('css/font-awesome.css')}}" />
<link rel="stylesheet" href="{{asset('css/tempusdominus-bootstrap-4.min.css')}}" /><!-- для календариків з годинником -->
<div class="relative flex items-top justify-center min-h-screen  sm:items-center sm:pt-0">
   <div class="container jumbotron mt-3" style="padding-top: 10; margin-top:0;  margin-bottom:5px;"> 

      @livewire('measure',[ 
         'mode' => $mode,
         'measures_rs' => $measures_rs,
         'maxIdMeasure' => $maxIdMeasure,
         'count_meas_row' => $count_meas_row,
         'naryadRecord' => $naryadRecord
         ])


<div class="row" style="margin-left:2pt; margin-right:2pt;"> <!--  кнопки переходу "назад-вперед" з методом POST -->
   
   <form action="{{url('naryads/'.$naryadRecord['order_id'].'/reedit3')}}" method="POST">
      @csrf
      @method('POST')   
      <div style="margin-right:600pt;">
         <button type="submit" 
         class="fa-hover btn btn-warning" 
         <i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>  назад</button> 
      </div>        
   </form>
   <form action="{{url('naryads/'.$naryadRecord['order_id'].'/editpart5')}}" method="POST">
      @csrf
      @method('POST')   
      <div  style="float: right; margin-right: 10px;">
         <button type="submit" 
         class="fa-hover btn btn-info" style="float: right;"
         formaction="{{url('naryads/'.$naryadRecord['order_id'].'/editpart5')}}" 
         formmethod="post">
         далі <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i></button>
      </div>
   </form>
  </div>

  
   </div> <!-- end conteiner  -->
</div>


@endsection
@livewireScripts