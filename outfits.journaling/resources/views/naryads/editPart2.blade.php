@extends('layouts.app')
@livewireStyles
@section('content')
@include('naryads.edit.f1HidenFields')  <!-- підключаємо приховані поля вводу для обміну з js  -->
<div class="relative flex items-top justify-center min-h-screen  sm:items-center sm:pt-0">
  <div class="container jumbotron mt-3" style="padding-top: 10; margin-top:0;  margin-bottom:5px;">   

@livewire('preparation',[
    'substations'=>$substations,
    'preparations_rs' => $preparations_rs,
    'maxIdpreparation' => $maxIdpreparation,
    'count_prepr_row' => $count_prepr_row,
    'naryadRecord' => $naryadRecord
    ])
 <hr class="mb-4"> 
 
 <div class="row" style="margin-left:2pt; margin-right:2pt;"> <!--  кнопки переходу "назад-вперед" з методом POST -->     
    <div style="margin-right:670pt;">
    <form action="{{url('naryads/'.$naryadRecord['order_id'].'/reedit')}}" method="POST">
        @csrf
        @method('POST')
       <button type="submit" class="fa-hover btn btn-warning"><i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>  назад</button>    
     </form>
    </div>         
    <form action="{{url('naryads/'.$naryadRecord['order_id'].'/editpart3')}}" method="POST">
        @csrf
        @method('POST')
        <button type="submit" class="fa-hover btn btn-info" style="float: right;">  далі <i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i></button>
     </form>
    </div>   
</div>
</div>
    
@endsection
<script type="text/javascript">
    function SetSelectedObjPrepare(myobj)  // прописуємо вибрану станцію із списка в поле введення
    {
         document.getElementById("preparationTarget_obj").value=myobj;        
    }
</script>
<script type="text/javascript" src="{{asset('js/jquery.min.js')}}" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script type="text/javascript" src="{{ asset('js/popper.min.js')}}"  integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js')}}" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
@livewireScripts
