<?php

namespace App\Http\Controllers\Ejournal\Edit;

use App\Model\Ejournal\Dicts\TypicalTask;
use App\Model\Ejournal\Dicts\WorksSpec;
use App\Model\Ejournal\Order;
use Livewire\Component;

class DirectionTask extends Component
{
    public array $changedOrderRecord = [];
    private int $unitId;
    private array $lines;
    private array $substations;
    private array $taskslist;
    private array $workspecs = [];
    public $choosedSubstation;
    private string $workslist = '';
    private int $branchId = 0;
    private int $worksSpecsId = 2;
    private string $mode = '';
    private int $substation_type_id = 0;
    private EditRepository $repo;

    public function mount(array $substations, string $mode, Order $orderRecord)
    {
        $this->reset();
        $this->mode = $mode;
        $this->repo = new EditRepository;
        $this->changedOrderRecord = $orderRecord->toArray();
        $this->workspecs = WorksSpec::getWorksSpecs();
        $this->worksSpecsId = $orderRecord->works_spec_id;
        $this->choosedSubstation = $orderRecord->substation_id;
        $this->branchId = $orderRecord->branch_id;
        $this->workslist = $orderRecord->objects . ' ' . $orderRecord->tasks;
        $this->substations = $substations;
        $this->substation_type_id = $substations[0]['type_id'];
        if ($mode !== 'create') {
            $this->line_id = $orderRecord->line_id;
            $this->lines = $this->repo->getLinesList($this->branchId, $orderRecord->substation_id);
        }
        $this->taskslist = TypicalTask::getListArray($this->worksSpecsId);// список робіт визначених, в функціях mount або choose_direction
    }

    public function directDialer($choice)
    {
        $this->worksSpecsId = $choice;
        // тільки при works_specs_id==3 , що означає =
        // тільки для 10-ток
        // буде зміна типу підстанцій (і тому й переліку в dict_substations), а так "завжди =0,4"
        if ($this->worksSpecsId == 3) {
            $this->substation_type_id = 2;
        } else {
            $this->substation_type_id = 1;
        }
        $this->taskslist = TypicalTask::getListArray($this->worksSpecsId); // список робіт визначених, в функціях mount або choose_direction
        $this->substations = $this->repo->getSubstationsList($this->changedOrderRecord['branch_id'], $this->substation_type_id);
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
        $this->taskslist = TypicalTask::getListArray($this->worksSpecsId); // список робіт визначених, в функціях mount або choose_direction
        $this->substations = $this->repo->getSubstationsList($this->changedOrderRecord['branch_id'], $this->substation_type_id);
        $this->lines = $this->repo->getLinesList($this->changedOrderRecord['branch_id'], $this->choosedSubstation);

        return view('orders.edit.DirectionTask', [
            'lines' => $this->lines,
            'workspecs' => $this->workspecs,
            'worksSpecsId' => $this->worksSpecsId,
            'substations' => $this->substations,
            'taskslist' => $this->taskslist,
            'substation_id' => $this->choosedSubstation,
            'substation_type_id' => $this->substation_type_id,
            'mode' => $this->mode,
            'workslist' => $this->workslist,
            'orderRecord' => $this->changedOrderRecord,
        ]);
    }
}
