<!-- останній рядок в Табл.2 = форма додавання запису в масив "надавачі дозволу на підготовку робочих місць ..." -->  
<tr>
    <td style="padding: 0 0 0 0">
      <div class="dropright input-group flex-nowrap">
            <input type="text" class="form-control @error('licensor') is-invalid @enderror" id="measureLicensor" placeholder="<- введіть дані" wire:model="licensor"> 
            @error('licensor') <span class="text-danger">{{ $message }}</span>@enderror
      </div>      
    </td>
    <td style="padding: 0 0 0 0">
      <div class="input-group flex-nowrap">
           <input type="text" class="form-control @error('lic_date') is-invalid @enderror" id="measureLic_date"  placeholder="<- введіть дату" wire:model="lic_date">
           @error('lic_date') <span class="text-danger">{{ $message }}</span> @enderror
      </div>                                                        
    </td>                                                  
    <td style="padding: 0 0 0 0">
        <button wire:click="measureStore()" 
             class="btn btn-primary" 
             type = "submit"
             name = "measureStore"
             style="margin-right: 3px;>
          <span class="caret"><i class="fa fa-save fa-fw"></i>+</span></button>
      </div>
    </td>
   </tr>

