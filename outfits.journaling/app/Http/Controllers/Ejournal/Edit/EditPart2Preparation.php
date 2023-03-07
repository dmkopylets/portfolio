<?php

namespace App\Http\Controllers\Ejournal\Edit;

use App\Http\Requests\EditOrderPart2Request;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class EditPart2Preparation extends Component
{
    public bool $updatePreparation = false;
    public array $orderRecord = [];
    public array $preparations = [];
    public int $rowKey = 0; // індекс рядка (одномірного масива) у бегатомірному масиві $preparation
    public $substationId, $substations, $maxIdPreparation, $preparationId, $preparationTargetObj, $preparationBody, $countRowPreparations;


    public function mount($substations, $preparations, $maxIdPreparation, $countRowPreparations, $orderRecordDTO)
    {
        $this->reset();
//        $this->orderRecord = $orderRecordDTO->toArray();
//        $this->branchId = $orderRecordDTO->branchId;
        $this->substationId = $orderRecordDTO->substationId;
        $this->substations = $substations;
        $this->preparations = $preparations;
        $this->maxIdPreparation = $maxIdPreparation;
        $this->countRowPreparations = $countRowPreparations;
    }

    protected $listeners = [
        'destroyPreparation' => 'destroy',
        //  'editPreparation'=>'edit',
        //  'updatePreparation'=>'update'
    ];


    public function render()
    {
        var_dump($this->preparations);
        return view('orders.edit.editPart2_Preparation', [
            'substations' => $this->substations,
            'substationId' => $this->substationId,
            'preparations' => $this->preparations,
            'countRowPreparations' => $this->countRowPreparations,
            'maxIdPreparation' => $this->maxIdPreparation,
            'updatePreparation' => $this->updatePreparation,
            'orderRecord' => $this->orderRecord,
        ]);
    }

    public function resetFields()
    {
        $this->preparationTargetObj = '';
        $this->preparationBody = '';
    }

    public function preparationStore()
    {
        // додаяється лише один рядочок
        // Validate Form Request
        $request = new EditOrderPart2Request();
        $validatedData = $this->validate();

        $v = Validator::make($request->all(), $request->rules(), $request->messages());
        if (!$v->fails()){
            $this->maxIdPreparation = $this->maxIdPreparation + 1;
            $this->preparations[] = [
                'id' => $this->maxIdPreparation,
                'preparationTargetObj' => $validatedData['preparationTargetObj'],
                'preparationBody' => $validatedData['preparationBody']
                ];
            $this->countRowPreparations = count($this->preparations);
            // заганяємо оновлені дані по підготовкам в session
            session(['preparations' => $this->preparations]);
            // Set Flash Message
            session()->flash('success', 'Препарація створена успішно!!');

            // Reset Form Fields After Creating Preparation
        } else {
            // Set Flash Message
            session()->flash('error', 'Під час створення Препарації сталася помилка!!');

            // Reset Form Fields After Creating Preparation
        }
        $this->resetFields();

        $this->countRowPreparations = count($this->preparations);
            $this->maxIdPreparation = max(array_column($this->preparations, 'id'));
            return view('orders.edit.editPart2_Preparation', [
                'substations' => $this->substations,
                'substationId' => $this->substationId,
                'preparations' => $this->preparations,
                'countRowPreparations' => $this->countRowPreparations,
                'maxIdPreparation' => $this->maxIdPreparation,
            ]);

        //$this->cancel();
    }

    public function editPreparation($id)
    {
        $this->preparationId = $id;
        $this->rowKey = array_search($id, array_column($this->preparations, 'id')); // індекс рядка (одномірного масива) у бегатомірному масиві $preparation
        $this->preparationTargetObj = $this->preparations[$this->rowKey]['targetObj'];
        $this->preparationBody = $this->preparations[$this->rowKey]['body'];
        //-----------------------
        $this->updatePreparation = true;
    }

    public function cancel()
    {
        $this->updatePreparation = false;
        $this->resetFields();
    }

    public function preparationUpdate()
    {
        // Validate requestd
        $this->validate();
        try {
            // Update Preparation
            $this->preparations[$this->rowKey] = [
                'id' => $this->preparationId,
                'preparationTargetObj' => $this->preparationTargetObj,
                'preparationBody' => $this->preparationBody,
                ];
            session(['preparations' => $this->preparations]);
            session()->flash('success', 'Препарація змінена успішно!!');
            $this->cancel();
        } catch (\Exception $e) {
            session()->flash('error', 'Під час оновлення Препарації сталася помилка!!');
            $this->cancel();
        }
    }

    public function destroy($id)
    {
        try {
            $key = array_search($id, array_column($this->preparations, 'id'));
            unset($this->preparations[$key]);
            $this->preparations = array_values($this->preparations); // переіндексація масива
            session(['preparations' => $this->preparations]);
            session()->flash('success', "Препарація видалена успішно!!!!");
            $this->cancel(); // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        } catch (\Exception $e) {
            session()->flash('error', "Під час видалення Препарації сталася помилка!!");
        } finally {
            $this->countRowPreparations = count($this->preparations);
            $this->maxIdPreparation = max(array_column($this->preparations, 'id'));
            return view('orders.edit.editPart2_Preparation', [
                'substations' => $this->substations,
                'substationId' => $this->substationId,
                'preparations' => $this->preparations,
                'countRowPreparations' => $this->countRowPreparations,
                'maxIdPreparation' => $this->maxIdPreparation,
            ]);
        }
        $this->cancel();
    }
}
