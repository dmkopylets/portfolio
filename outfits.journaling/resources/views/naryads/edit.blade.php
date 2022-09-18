@extends('layouts.app')
@livewireStyles
@section('content')
<link rel="stylesheet" href="{{asset('css/font-awesome.css')}}" />
<link rel="stylesheet" href="{{asset('css/tempusdominus-bootstrap-4.min.css')}}" /><!-- для календариків з годинником -->
<div class="container">
<div class="jumbotron mt-3" style="padding-top: 0; margin-top:0;">           

    {{ Form::model('Naryads', array('url' => array('naryads/'.$naryadRecord['order_id'].'/editpart2'), 'method' => 'POST')) }}

        @include('naryads.edit.f1HidenFields')  <!-- підключаємо приховані поля вводу для обміну з js  -->
              <div class="text-lg-left"  >
                   <div class="row"><div class="col-md-3"><p class="lead" style="font-size: 12pt;">підприємство</p></div><div class="col-md-6"><h3 style="font-size: 14pt;">АТ "ANYОБЛЕНЕРГО" {{'mode='.$mode}}</h3></div></div>
                   <div class="row"><div class="col-md-3"><p class="lead" style="font-size: 12pt;">підрозділ</p>   </div><div class="col-md-6">
                            <div class="input-group flex-nowrap">
                                <div class="input-group-prepend">
                                    <label style="font-size: 14pt;" for="district">{{$branch->body}}</label>&nbsp<label>, дільниця:</label>&nbsp
                                </div>
                                <select style="width:80px; height: 12pt; font-size: 10pt;  vertical-align:bottom;" id="district" name="district" required>
                                    @foreach($units as $unit)
                                        <option 
                                           @if (($mode !=='create') and ($unit->id == $naryadRecord['unit_id']))
                                                {{' selected = true '}}
                                           @endif     
                                           VALUE="{{$unit->id}}">{{$unit->body}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
              </div>

            <div class="text-lg-center" style="padding-top:0; margin-top:0;">
                <h1 style="font-size: 16pt;">НАРЯД-ДОПУСК</h1>   
                <p class="lead">(Для виконання робіт в електроустановках)</p>
            </div>

<div class="row">
  @include('naryads.edit.f2Rpanel')  <!-- Права Панелька з чекбоксами вибору членів бригади  -->

<!-- Ліва частина "робоча частина"  -->
   <div class="col-md-8 order-md-1">
      @include('naryads.edit.f3BrigadeBody')            <!-- "хто робитиме"  -->
      <!-- "що і де робити"  // вибір select-ами ктп... 
      визов "фрейма" edit.f4_direction-task виконується через контролер livewire.direction-task -->
      @livewire('direction-task',[
          'workspecs'=>$workspecs,
          'substations' => $substations,
          'mode' => $mode,
          'naryadRecord' => $naryadRecord,
          ])   
   </div>
</div>

<hr class="mb-4">  <!-- просто розділова горизонтальн рисочка через всю форму -->
    @include('naryads.edit.f5Calendares')
<hr class="mb-4">  <!-- просто розділова горизонтальн рисочка через всю форму -->



    <button 
           class="btn btn-primary btn-lg btn-block" 
           type = "submit"
             >Далі Заходи щодо підготовки робочих місць =>
    </button>     
{{ Form::close() }}        
          </div>
        </div>
  
</div>

@include('naryads.edit.js')
@livewireScripts
@endsection

