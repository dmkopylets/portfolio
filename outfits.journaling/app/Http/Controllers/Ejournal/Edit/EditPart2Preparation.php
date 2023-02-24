<?php

namespace App\Http\Controllers\Ejournal\Edit;

use App\Model\Ejournal\OrderRecordDTO;
use Livewire\Component;

class EditPart2Preparation extends Component
{
    public bool $updatePreparation = false;
    public array $orderRecord = [];
    private OrderRecordDTO $orderRecordDTO;
    public int $rowkey = 0; // індекс рядка (одномірного масива) у бегатомірному масиві $preparation
    public $branchId, $substationId, $substations, $preparations, $maxIdPreparation, $preparation_id, $targetObj, $body, $countRowPreparations;


    public function mount($substations, $preparations, $maxIdPreparation, $countRowPreparations, $orderRecordDTO, $editRepository)
    {
        $this->reset();
        $this->orderRecordDTO = $orderRecordDTO;
        $this->orderRecord = $this->orderRecordDTO->toArray();
        $this->branchId = $orderRecordDTO->branchId;
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


// Validation Rules
    protected $rules = [
        'targetObj' => 'required',
        'body' => 'required'
    ];

    public function render()
    {
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
        $this->targetObj = '';
        $this->body = '';
    }

    public function preparationStore()
    {
        // додаяється лише один рядочок
        // Validate Form Request
        $this->validate();
        try {
            $this->maxIdPreparation = $this->maxIdPreparation + 1;
            $this->preparations[] = [
                'id' => $this->maxIdPreparation,
                'targetObj' => $this->targetObj,
                'body' => $this->body];
            $this->countRowPreparations = count($this->preparations);
            // заганяємо оновлені дані по підготовкам в session
            session(['preparations' => $this->preparations]);
            // Set Flash Message
            session()->flash('success', 'Препарація створена успішно!!');

            // Reset Form Fields After Creating Preparation
            $this->resetFields();
        } catch (\Exception $e) {
            // Set Flash Message
            session()->flash('error', 'Під час створення Препарації сталася помилка!!');

            // Reset Form Fields After Creating Preparation
            $this->resetFields();
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

    public function editPreparation($id)
    {
        $this->preparation_id = $id;
        $this->rowkey = array_search($id, array_column($this->preparations, 'id')); // індекс рядка (одномірного масива) у бегатомірному масиві $preparation_rs
        $this->targetObj = $this->preparations[$this->rowkey]['targetObj'];
        $this->body = $this->preparations[$this->rowkey]['body'];
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
            $this->preparations[$this->rowkey] = [
                'id' => $this->preparation_id,
                'targetObj' => $this->targetObj,
                'body' => $this->body];
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
            return view('orders.edit.f6Preparation', [
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
