<!-- останній рядок в Табл.1 = форма додавання запису в масив "заходи щодо підготовки ..." -->  
<tr>
    <td style="padding: 0 0 0 0">
      <div class="dropright input-group flex-nowrap">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" style="margin-right: 3px;" id="prepObjBtn">
              <span class="caret"><i class="fa fa-list-ul"></i></span></button>
              <!-- Випадаючий список підстанцій -->
              <ul class="dropdown-menu scrollable-menu_prepobj" role="menu" >
                  @foreach($substations as $substation)
                    <li><a class="dropdown-item"  
                       @if ($substation->id == $substation_id)
                          {{ $selectedSubtation=$substation->body}}
                          {{" active=true " }} 
                       @endif 
                      id="prObj{{$substation->id}}"  
                          href="javascript:SetSelectedObjPrepare('{{$substation->body}}');"
                          >{{$substation->body}}
                    </a></li>
                  @endforeach
              </ul>           
            <input type="text" class="form-control @error('Target_obj') is-invalid @enderror" id="preparationTarget_obj" 
            name="preparationTarget_obj" placeholder="<-виберіть або введіть підстанцію" wire:model="target_obj" value={{$selectedSubtation}}>
            @error('Target_obj') <span class="text-danger">{{ $message }}</span>@enderror
      </div>      
    </td>
    <td style="padding: 0 0 0 0">
      <div class="input-group flex-nowrap">
           <input type="text" class="form-control @error('body') is-invalid @enderror" id="preparationBody" wire:model="body" placeholder="що виконати">
                                                  @error('body') <span class="text-danger">{{ $message }}</span> @enderror
     <td style="padding: 0 0 0 0">
        <button wire:click="preparationStore()" 
             class="btn btn-primary" 
             type = "submit"
             name = "prepatationStore"
             style="margin-right: 3px;>
          <span class="caret"><i class="fa fa-save fa-fw"></i>+</span></button>
      </div>
    </td>
   </tr>

