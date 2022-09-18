
<div class="measure"> 
<!-- таблиця 2  -->
 <h4 class="mb-3" style="margin-bottom: 0; vertical-align: bottom; line-height: 1pt;">Таблиця 2</h4>
 <h3 class="py-2 text-lg-center" style="margin-top: 10px; background: rgb(24, 38, 51);  color: white; "><b>Дозвіл на підготовку робочих місць і на допуск</b></h3>

 <!--  інфа для отладки  
 '$mode='.$mode.' //__ $count_meas_rows='.$count_meas_row.' //___ maxIdMeasure='.$maxIdMeasure.' //___ $measures_rs='.json_encode($measures_rs)-->
 
  <table class="table  table-bordered table-sm" style="margin-top:0; margin-bottom: 1px;">
    <thead class="thead-dark">
      <tr valign="middle" align="center" style="line-height: 80%;">
          <th scope="col" width="300px">Дозвіл на підготовку робочих місць і на допуск видав <br /> (посада, прізвище або підпис) </th>
          <th scope="col" width="120px">Дата, час</th>
          <th scope="col" width="160px">Підпис працівника, який отримав<br />дозвіл на підготовку робочих<br />місць і на допуск</th>                    
        </tr>
       <tr valign="middle" align="center" style="line-height: 70%;">
          <th><em> 1 </em></th>
          <th><em> 2 </em></th>
          <th><em> 3 </em></th>
        </tr>    
    </thead>      
      <tbody>
        @if ($count_meas_row > 0)
            @foreach ($measures_rs as $mrow)
                <tr>
                    <td>
                      <em>{{$mrow['licensor']}}</em>
                    </td>
                    <td>
                      <em>{{$mrow['lic_date']}}</em>
                    </td>
                    <td>
                        <button wire:click="editMeasure({{$mrow['id']}})" class="btn btn-primary btn-sm"><i class="fa fa-pencil fa-fw"></i>{{$mrow['id']}}</button>
                        <button wire:click="$emit('destroyMeasure',{{$mrow['id']}})" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="3" align="center">
                    Немає даних.
                </td>
            </tr>
        @endif
            @if($updateMeasure)
                @include('naryads.edit.measure.update')
            @else
                @include('naryads.edit.measure.create')
            @endif
        </tbody>

</table>
<!--  Вивід результатів роботи з рядками таблиці  -->
  @if(session()->has('success'))
    <div class="alert alert-success" role="alert">
        {{ session()->get('success') }}
    </div>
        {{ session(['measures_rs' => $measures_rs])}}
  @endif
  @if(session()->has('error'))
    <div class="alert alert-danger" role="alert">
        {{ session()->get('error') }}
    </div>
  @endif
<!--  кінець вивіду результатів роботи з рядками таблиці    -->
</div>   