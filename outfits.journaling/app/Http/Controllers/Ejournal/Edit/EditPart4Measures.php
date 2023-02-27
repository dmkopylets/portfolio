<?php
namespace App\Http\Controllers\Ejournal\Edit;
use Livewire\Component;
use Redirect;

class EditPart4Measures extends Component
{
    public $updateMeasure = false;
    public $rowkey;     // індекс рядка (одномірного масива) у двомірному масиві $measure_rs
    public $measure_id; // номер рядка для для запису в таблицю бази даних в поле id (не номер в масиві !  )
    public $branchId, $measures, $maxIdMeasure, $count_meas_row, $orderRecord, $licensor, $lic_date, $mode;

    public function mount($measures, $maxIdMeasure, $count_meas_row, $orderRecord)
    {
        $this->reset();
        $this->branchId = $orderRecord['branchId'];
        $this->mode = $orderRecord['editMode'];
        $this->measures = $measures;
        $this->maxIdMeasure =$maxIdMeasure;
        $this->count_meas_row = $count_meas_row;
        $this->orderRecord = $orderRecord;
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
        return view('orders.edit.editPart4_Measures',[
           'measures'=>$this->measures,
           'mode'=>$this->mode,
           'count_meas_row'=>$this->count_meas_row,
           'maxIdMeasure'=>$this->maxIdMeasure,
           'updateMeasure'=>$this->updateMeasure,
           'orderRecord'=>$this->orderRecord
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
            $this->measures[]=[
                'id'      =>$this->maxIdMeasure,
                'licensor'=>$this->licensor,
                'lic_date'=>$this->lic_date];
            $this->count_meas_row = count($this->measures);
            // заганяємо оновлені дані по підготовкам в session
            session(['measures'  => $this->measures]);
            // Set Flash Message
            session()->flash('success','рядок створено успішно!!');

            // Reset Form Fields After Creating EditPart4Measures
            $this->resetFields();
        }catch(\Exception $e){
            // Set Flash Message
            session()->flash('error','Під час створення рядка сталася помилка!!');

            // Reset Form Fields After Creating EditPart4Measures
            $this->resetFields();
        }
        finally{
            $this->count_meas_row = count($this->measures);
            $this->maxIdMeasure = max(array_column($this->measures,'id'));
            return view('orders.edit.f7Measures',[
                'count_meas_row'=>$this->count_meas_row,
                'maxIdMeasure'=>$this->maxIdMeasure,
                'measures'=>$this->measures,
                 ]);
        }
        $this->cancel();
    }

    public function editMeasure($id){
        $this->measure_id = $id; // номер рядка для таблиці бази даних (не номер в масиві!) буде використано в measureUpdate()
        $this->rowkey = array_search($id, array_column($this->measures, 'id')); // індекс рядка (одномірного масива) у двомірному масиві $measure_rs
        $this->licensor =  $this->measures[$this->rowkey]['licensor'];
        $this->lic_date =  $this->measures[$this->rowkey]['lic_date'];
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
            // Update EditPart4Measures
            $this->measures[$this->rowkey]=[
                     'id'=>$this->measure_id,  // "прилітає з editMeasure($id)
                     'licensor'=>$this->licensor,
                     'lic_date'=>$this->lic_date];
            session(['measures'  => $this->measures]);
            session()->flash('success','рядок змінено успішно!!');
            $this->cancel();
        }catch(\Exception $e){
            session()->flash('error','Під час оновлення рядка сталася помилка!!');
            $this->cancel();
        }
    }

    public function destroy($id){
        try{
            $key = array_search($id, array_column($this->measures, 'id'));
            unset($this->measures[$key]);
            $this->measures=array_values($this->measures);  // переіндексація масива
            session(['measures'  => $this->measures]);
            session()->flash('success',"рядок видалено успішно!!!!");
            $this->cancel();
        }catch(\Exception $e){
            session()->flash('error',"Під час видалення рядка сталася помилка!!");
        }
        finally{
            $this->count_meas_row = count($this->measures);
            $this->maxIdMeasure = max(array_column($this->measures,'id'));
            return view('orders.edit.f7Measures',[
                'measures'=>$this->measures,
                'count_meas_row'=>$this->count_meas_row,
                'maxIdMeasure'=>$this->maxIdMeasure,
                 ]);
        }
        $this->cancel();
    }
}
