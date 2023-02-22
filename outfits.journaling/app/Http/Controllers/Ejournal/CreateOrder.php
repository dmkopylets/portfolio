<?php

namespace App\Http\Controllers\Ejournal;

use App\Http\Controllers\Ejournal\Edit\EditRepository;
use App\Model\Ejournal\Dicts\Adjuster;
use App\Model\Ejournal\Dicts\TypicalTask;
use App\Model\Ejournal\Dicts\Unit;
use App\Model\Ejournal\Dicts\Warden;
use App\Model\Ejournal\Dicts\WorksSpec;
use App\Model\User\Entity\BranchInfo;
use Illuminate\Http\Request;

class CreateOrder
{
    public function __construct(EditRepository $repo, BranchInfo $branch, EjournalController $ejournalController)
    {
        $this->ejournalController = $ejournalController;
        $this->branch = $branch;
        $this->repo = $repo;
    }

    /**
     *   !! створюєно НОВИЙ наряд
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $worksSpecsId = $request->input('direction'); // визначена позиція списка - де робитиметься :
        // тільки для "10-ток" буде зміна типу підстанцій (і тому й переліку в dict_substations), а так "завжди =0,4"
        if ($worksSpecsId == 3) {
            $substationTypeId = 2;
        } else {
            $substationTypeId = 1;
        }

        $newOrder = $this->repo->initOrderRecord($this->branch->id, $worksSpecsId);
        $tasks = TypicalTask::orderBy('id')->get();
        $units = Unit::where('branch_id', $this->branch->id)->orderBy('id')->get();
        $wardens = Warden::where('branch_id', $this->branch->id)->orderBy('id')->get();
        $adjusters = Adjuster::where('branch_id', $this->branch->id)->orderBy('id')->get();
        $allPossibleTeamMembers = $this->repo->getAllPossibleTeamMembersArray($this->branch->id);
        $allPossibleTeamEngineer = $this->repo->getAllPossibleTeamEngineerArray($this->branch->id);
        $substantions = $this->repo->getSubstationsList($this->branch->id, $substationTypeId);
        $this->ejournalController->setOrderRecord($newOrder);
        $this->ejournalController->setMode('create');

        return view('orders.edit.editPart1', [
            'orderRecord' => $newOrder,
            'mode' => 'create',
            'title' => 'новий',
            'tasks' => $tasks,
            'units' => $units,
            'wardens' => $wardens,
            'adjusters' => $adjusters,
            'allPossibleTeamMembers' => $allPossibleTeamMembers,
            'allPossibleTeamEngineer' => $allPossibleTeamEngineer,
            'branch' => $this->branch,
            'substations' => $substantions,
            'workspecs' => WorksSpec::getWorksSpecs(), // список - де робитиметься : на 10-ках, чи на 0.4, чи ...
            'workslist' => ' виконати ', // саме текст завдання
            'maxIdpreparation' => 0,
            'maxIdmeasure' => 0,
            'editRopository' => $this->repo,
        ]);
    }
}
