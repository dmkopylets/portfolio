<?php

namespace App\Http\Controllers\Ejournal\Edit;

use App\Model\Ejournal\Dicts\Line;
use App\Model\Ejournal\Dicts\Substation;
use App\Model\Ejournal\Dicts\TypicalTask;
use App\Model\Ejournal\Dicts\WorksSpec;
use App\Model\Ejournal\OrderRecordDTO;
use Livewire\Component;

class DirectionTask extends Component
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
        $this->workspecs = WorksSpec::getWorksSpecs();
        $this->worksSpecsId = $this->changedOrderRecord['worksSpecsId'];
        $this->choosedSubstation = $this->changedOrderRecord['substationId'];
        $this->substationTypeId = Substation::getTypeId($this->choosedSubstation);
        $this->branchId = $orderRecord->branchId;
        $this->workslist = $orderRecord->objects . ' ' . $orderRecord->tasks;
        $this->taskslist = TypicalTask::getListArray($this->worksSpecsId);// список робіт визначених, в функціях mount або choose_direction
        $this->substations = Substation::getListArray($this->branchId, $this->substationTypeId);
        if ($mode !== 'create') {
            $this->lineId = $orderRecord->lineId;
            $this->lines = $this->repo->getLinesList($this->branchId, $orderRecord->substationId);
        }
    }

    public function directDialer($choice)
    {
        $this->worksSpecsId = $choice;
        $this->changedOrderRecord['worksSpecsId'] = $choice;
        $this->reset('substationTypeId');
        $this->reset('substations');
        // тільки при works_specs_id==3 , що означає =
        // тільки для 10-ток
        // буде зміна типу підстанцій (і тому й переліку в dict_substations), а так "завжди =0,4"
        if ($this->worksSpecsId === 3) {
            $this->substationTypeId = 2;
        }

        $this->taskslist = TypicalTask::getListArray($this->worksSpecsId); // список робіт визначених, в функціях mount або choose_direction
        $this->reset('substations');
        $this->substations = Substation::getListArray($this->branchId, $this->substationTypeId);
    }

    public function substationDialer($choice)
    {
        $this->reset('choosedSubstation');
        $this->choosedSubstation = $choice;
        $this->reset('lines');
        $this->lines = Line::getListArray($this->branchId, $choice);
    }

    public function render()
    {
        $this->taskslist = TypicalTask::getListArray($this->worksSpecsId); // список робіт визначених, в функціях mount або choose_direction
        $this->substations = Substation::getListArray($this->branchId, $this->substationTypeId);
        $this->lines = Line::getListArray($this->branchId, $this->choosedSubstation);
        $this->changedOrderRecord['worksSpecsId'] = $this->worksSpecsId;

        return view('orders.edit.DirectionTask', [
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
