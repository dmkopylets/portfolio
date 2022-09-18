<?php
namespace App\Http\Livewire;
use Livewire\Component;
use Redirect;

class Measure extends Component
{
    public $updateMeasure = false;
    public $rowkey;     // індекс рядка (одномірного масива) у двомірному масиві $measure_rs
    public $measure_id; // номер рядка для для запису в таблицю бази даних в поле id (не номер в масиві !  )
    public $branch_id, $measures_rs, $maxIdMeasure, $count_meas_row, $naryadRecord, $licensor, $lic_date, $mode;
    
    public function mount($mode, $measures_rs, $maxIdMeasure, $count_meas_row, $naryadRecord)
    {
        $this->reset();
        $this->branch_id = $naryadRecord['branch_id'];
        $this->mode = $mode;
        $this->measures_rs = $measures_rs;
        $this->maxIdMeasure =$maxIdMeasure;
        $this->count_meas_row = $count_meas_row;
        $this->naryadRecord = $naryadRecord;
    }

    protected $listeners = [
        'destroyMeasure'=>'destroy',
      //  'editMeasure'=>'edit',
      //  'updateMeasure'=>'update'
    ];


// Validation Rules
    protected $rules = [
        'licensor'=>'required',
        'lic_date'=>'required'
    ];

    public function render()
    {
        return view('naryads.edit.f7Measures',[
           'measures_rs'=>$this->measures_rs,
           'mode'=>$this->mode,
           'count_meas_row'=>$this->count_meas_row,
           'maxIdMeasure'=>$this->maxIdMeasure,
           'updateMeasure'=>$this->updateMeasure,
           'naryadRecord'=>$this->naryadRecord
            ]);
    }

    public function resetFields(){
        $this->licensor = '';
        $this->lic_date = '';
    }

    public function measureStore(){
        // додаяється лише один рядочок
        // Validate Form Request
        $this->validate();
        try{
            $this->maxIdMeasure++;
            $this->measures_rs[]=[
                'id'      =>$this->maxIdMeasure,
                'licensor'=>$this->licensor,
                'lic_date'=>$this->lic_date];
            $this->count_meas_row = count($this->measures_rs);
            // заганяємо оновлені дані по підготовкам в session
            session(['measures_rs'  => $this->measures_rs]);
            // Set Flash Message
            session()->flash('success','рядок створено успішно!!');

            // Reset Form Fields After Creating Measure
            $this->resetFields();
        }catch(\Exception $e){
            // Set Flash Message
            session()->flash('error','Під час створення рядка сталася помилка!!');

            // Reset Form Fields After Creating Measure
            $this->resetFields();
        }
        finally{
            $this->count_meas_row = count($this->measures_rs);       
            $this->maxIdMeasure = max(array_column($this->measures_rs,'id'));
            return view('naryads.edit.f7Measures',[
                'count_meas_row'=>$this->count_meas_row,
                'maxIdMeasure'=>$this->maxIdMeasure,
                'measures_rs'=>$this->measures_rs,
                 ]);
        }
        $this->cancel();
    }

    public function editMeasure($id){
        $this->measure_id = $id; // номер рядка для таблиці бази даних (не номер в масиві!) буде використано в measureUpdate()
        $this->rowkey = array_search($id, array_column($this->measures_rs, 'id')); // індекс рядка (одномірного масива) у двомірному масиві $measure_rs
        $this->licensor =  $this->measures_rs[$this->rowkey]['licensor'];
        $this->lic_date =  $this->measures_rs[$this->rowkey]['lic_date'];
        //-----------------------
        $this->updateMeasure = true;
    }

    public function cancel()
    {
        $this->updateMeasure = false;
        $this->resetFields();
    }

    public function measureUpdate(){
        // Validate requestd
        $this->validate();
        try{
            // Update Measure
            $this->measures_rs[$this->rowkey]=[
                     'id'=>$this->measure_id,  // "прилітає з editMeasure($id)
                     'licensor'=>$this->licensor,
                     'lic_date'=>$this->lic_date];
            session(['measures_rs'  => $this->measures_rs]);                     
            session()->flash('success','рядок змінено успішно!!');
            $this->cancel();
        }catch(\Exception $e){
            session()->flash('error','Під час оновлення рядка сталася помилка!!');
            $this->cancel();
        }
    }

    public function destroy($id){
        try{
            $key = array_search($id, array_column($this->measures_rs, 'id'));
            unset($this->measures_rs[$key]);
            $this->measures_rs=array_values($this->measures_rs);  // переіндексація масива
            session(['measures_rs'  => $this->measures_rs]);
            session()->flash('success',"рядок видалено успішно!!!!");
            $this->cancel(); 
        }catch(\Exception $e){
            session()->flash('error',"Під час видалення рядка сталася помилка!!");
        }
        finally{
            $this->count_meas_row = count($this->measures_rs);       
            $this->maxIdMeasure = max(array_column($this->measures_rs,'id'));
            return view('naryads.edit.f7Measures',[
                'measures_rs'=>$this->measures_rs,
                'count_meas_row'=>$this->count_meas_row,
                'maxIdMeasure'=>$this->maxIdMeasure,
                 ]);
        }
        $this->cancel();
    }
}
