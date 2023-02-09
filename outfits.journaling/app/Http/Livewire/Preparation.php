<?php
namespace App\Http\Livewire;
use Livewire\Component;


class Preparation extends Component
{
    public $updatePreparation = false;
    public $rowkey; // індекс рядка (одномірного масива) у бегатомірному масиві $preparation_rs
    public                $branch_id, $substation_type_id, $substation_id, $substations, $preparations_rs, $maxIdpreparation, $preparation_id, $target_obj, $body, $count_prepr_row, $naryadRecord;


    public function mount($substations, $preparations_rs, $maxIdpreparation, $count_prepr_row, $naryadRecord)
    {
        $this->reset();
        $this->branch_id = $naryadRecord['branch_id'];
        $this->substation_type_id = $naryadRecord['substation_type_id'];
        $this->substation_id = $naryadRecord['substation_id'];
        $this->substations = $substations;
        $this->preparations_rs = $preparations_rs;
        $this->maxIdpreparation =$maxIdpreparation;
        $this->count_prepr_row = $count_prepr_row;
        $this->naryadRecord = $naryadRecord;
    }

    protected $listeners = [
        'destroyPreparation'=>'destroy',
      //  'editPreparation'=>'edit',
      //  'updatePreparation'=>'update'
    ];


// Validation Rules
    protected $rules = [
        'target_obj'=>'required',
        'body'=>'required'
    ];

    public function render()
    {
        return view('orders.edit.f6Preparation',[
           'substations'=>$this->substations,
           'substation_id'=>$this->substation_id,
           'preparations_rs'=>$this->preparations_rs,
           'count_prepr_row'=>$this->count_prepr_row,
           'maxIdpreparation'=>$this->maxIdpreparation,
           'updatePreparation'=>$this->updatePreparation,
           'naryadRecord'=>$this->naryadRecord
            ]);
    }

    public function resetFields(){
        $this->target_obj = '';
        $this->body = '';
    }

    public function preparationStore(){
        // додаяється лише один рядочок
        // Validate Form Request
        $this->validate();
        try{
            $this->maxIdpreparation = $this->maxIdpreparation+1;
            $this->preparations_rs[]=[
                'id' => $this->maxIdpreparation,
                'target_obj'=>$this->target_obj,
                'body'=>$this->body];
            $this->count_prepr_row = count($this->preparations_rs);
            // заганяємо оновлені дані по підготовкам в session
            session(['preparations_rs' => $this->preparations_rs]);
            // Set Flash Message
            session()->flash('success','Препарація створена успішно!!');

            // Reset Form Fields After Creating Preparation
            $this->resetFields();
        }catch(\Exception $e){
            // Set Flash Message
            session()->flash('error','Під час створення Препарації сталася помилка!!');

            // Reset Form Fields After Creating Preparation
            $this->resetFields();
        }
        finally{
            $this->count_prepr_row = count($this->preparations_rs);
            $this->maxIdpreparation = max(array_column($this->preparations_rs,'id'));
            return view('orders.edit.f6Preparation',[
                'substations'=>$this->substations,
                'substation_id'=>$this->substation_id,
                'preparations_rs'=>$this->preparations_rs,
                'count_prepr_row'=>$this->count_prepr_row,
                'maxIdpreparation'=>$this->maxIdpreparation,
                'preparations_rs'=>$this->preparations_rs,
                 ]);
        }
        $this->cancel();
    }

    public function editPreparation($id){
        $this->preparation_id = $id;
        $this->rowkey = array_search($id, array_column($this->preparations_rs, 'id')); // індекс рядка (одномірного масива) у бегатомірному масиві $preparation_rs
        $this->target_obj =  $this->preparations_rs[$this->rowkey]['target_obj'];
        $this->body =        $this->preparations_rs[$this->rowkey]['body'];
        //-----------------------
        $this->updatePreparation = true;
    }

    public function cancel()
    {
        $this->updatePreparation = false;
        $this->resetFields();
    }

    public function preparationUpdate(){
        // Validate requestd
        $this->validate();
        try{
            // Update Preparation
            $this->preparations_rs[$this->rowkey]=[
                     'id'=>$this->preparation_id,
                     'target_obj'=>$this->target_obj,
                     'body'=>$this->body];
            session(['preparations_rs' => $this->preparations_rs]);
            session()->flash('success','Препарація змінена успішно!!');
            $this->cancel();
        }catch(\Exception $e){
            session()->flash('error','Під час оновлення Препарації сталася помилка!!');
            $this->cancel();
        }
    }

    public function destroy($id){
        try{
            $key = array_search($id, array_column($this->preparations_rs, 'id'));
            unset($this->preparations_rs[$key]);
            $this->preparations_rs=array_values($this->preparations_rs); // переіндексація масива
            session(['preparations_rs' => $this->preparations_rs]);
            session()->flash('success',"Препарація видалена успішно!!!!");
            $this->cancel(); // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        }catch(\Exception $e){
            session()->flash('error',"Під час видалення Препарації сталася помилка!!");
        }
        finally{
            $this->count_prepr_row = count($this->preparations_rs);
            $this->maxIdpreparation = max(array_column($this->preparations_rs,'id'));
            return view('orders.edit.f6Preparation',[
                'substations'=>$this->substations,
                'substation_id'=>$this->substation_id,
                'preparations_rs'=>$this->preparations_rs,
                'count_prepr_row'=>$this->count_prepr_row,
                'maxIdpreparation'=>$this->maxIdpreparation,
                'preparations_rs'=>$this->preparations_rs,
                 ]);
        }
        $this->cancel();
    }
}
