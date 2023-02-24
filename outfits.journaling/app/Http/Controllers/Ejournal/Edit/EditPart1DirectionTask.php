<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ejournal\Edit;

use App\Model\Ejournal\OrderRecordDTO;
use Livewire\Component;

class EditPart1DirectionTask extends Component
{
    public array $changedOrderRecord = [];
    public array $lines = [];
    public array $substations = [];
    public array $taskslist = [];
    public array $workspecs = [];
    public int $choosedSubstation = 0;
    private string $workslist = '';
    public int $branchId = 0;
    public int $worksSpecsId = 2;
    private string $mode = '';
    public int $substationTypeId = 1;
    private EditRepository $repo;
    public int $lineId = 1;

    public function mount(string $mode, OrderRecordDTO $orderRecord, EditRepository $editRopository)
    {
        $this->reset();
        $this->mode = $mode;
        $this->repo = $editRopository;
        $this->changedOrderRecord = $orderRecord->toArray();
        $this->workspecs = $editRopository->getWorksSpecs();
        $this->worksSpecsId = $this->changedOrderRecord['worksSpecsId'];
        $this->choosedSubstation = $this->changedOrderRecord['substationId'];
        $this->substationTypeId = $editRopository->getSubstationTypeId($this->choosedSubstation);
        $this->branchId = $orderRecord->branchId;
        $this->workslist = $orderRecord->objects . ' виконати ' . $orderRecord->tasks;
        $this->taskslist = $editRopository->getTypicalTasksListArray($this->worksSpecsId);// список робіт визначених, в функціях mount або choose_direction
        $this->substations = $editRopository->getSubstationsList($this->branchId, $this->substationTypeId);
        if ($mode !== 'create') {
            $this->lineId = $orderRecord->lineId;
            $this->lines = $this->repo->getLinesList($this->branchId, $orderRecord->substationId);
        }
    }

    public function directDialer($choice)
    {
        $this->worksSpecsId = $choice;
        $this->changedOrderRecord['worksSpecsId'] = (int)$choice;
        $this->reset('substationTypeId');
        $this->reset('substations');
        // тільки при works_specs_id==3 , що означає =
        // тільки для 10-ток
        // буде зміна типу підстанцій (і тому й переліку в dict_substations), а так "завжди =0,4"
        if ($this->worksSpecsId === 3) {
            $this->substationTypeId = 2;
        }
        $this->taskslist = $this->repo->getTypicalTasksListArray($this->worksSpecsId); // список робіт визначених, в функціях mount або choose_direction
        $this->reset('substations');
        $this->substations = $this->repo->getSubstationsList($this->branchId, $this->substationTypeId);
        $this->lines = $this->repo->getLinesList($this->changedOrderRecord['branchId'], $this->changedOrderRecord['substationId']);
    }

    public function substationDialer($choice)
    {
        $this->reset('choosedSubstation');
        $this->choosedSubstation = $choice;
        $this->reset('lines');
        $this->lines = $this->repo->getLinesList($this->branchId, $choice);
    }

    public function render()
    {
        $this->taskslist = $this->repo->getTypicalTasksListArray($this->worksSpecsId); // список робіт визначених, в функціях mount або choose_direction
        $this->substations = $this->repo->getSubstationsList($this->branchId, $this->substationTypeId);
        $this->lines = $this->repo->getLinesList($this->changedOrderRecord['branchId'], $this->changedOrderRecord['substationId']);
        $this->changedOrderRecord['worksSpecsId'] = $this->worksSpecsId;

        return view('orders.edit.EditPart1_DirectionTask', [
            'lines' => $this->lines,
            'workspecs' => $this->workspecs,
            'worksSpecsId' => $this->worksSpecsId,
            'taskslist' => $this->taskslist,
            'substations' => $this->substations,
            'choosedSubstation' => $this->choosedSubstation,
            'substationTypeId' => $this->substationTypeId,
            'mode' => $this->mode,
            'workslist' => $this->workslist,
            'changedOrderRecord' => $this->changedOrderRecord,
        ]);
    }
}
