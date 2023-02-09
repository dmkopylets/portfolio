    <input type="hidden" wire:model="measure_id">
    <tr>
    <td>
    <div class="form-group mb-3">
        <!--label for="measureLicensor">Видав дозвіл:</label-->
        <input type="text" class="form-control @error('licesor') is-invalid @enderror" id="measureLicensor" wire:model="licensor" placeholder="дозвіл видав">
        @error('licensor') <span class="text-danger">{{ $message }}</span>@enderror
    </div>
    </td>
    <td>
    <div class="form-group mb-3">
        <!--label for="measureLic_date">що вимкнути і заземлити:</label-->
        <input type="text" class="form-control @error('lic_date') is-invalid @enderror" id="measureLic_date" wire:model="lic_date" placeholder="дата дозволу">
        <!--textarea  class="form-control #error('lic_date') is-invalid #enderror" id="measureLic_date" wire:model="lic_date" placeholder="що виконати"></textarea-->
        @error('lic_date') <span class="text-danger">{{ $message }}</span>@enderror
    </div>
    </td>
    <td>   
        <button wire:click="measureUpdate()" class="btn btn-success"><i class="fa fa-save fa-fw"></i></button>
        <button wire:click="cancel()" class="btn btn-danger"><i class="fa fa-ban"></i></button>
    </td>
