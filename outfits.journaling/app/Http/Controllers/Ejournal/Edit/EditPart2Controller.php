<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ejournal\Edit;

use App\Http\Controllers\Ejournal\BaseController;
use App\Http\Controllers\Ejournal\EjournalController;
use App\Model\Ejournal\Dicts\Substation;
use App\Model\Ejournal\OrderRecordDTO;
use App\Model\Ejournal\Preparation;
use App\Model\User\Entity\BranchInfo;
use Illuminate\Http\Request;

class EditPart2Controller extends BaseController
{
    protected BranchInfo $branch;
    private EjournalController $ejournalController;
    private OrderRecordDTO $orderRecord;

    public function __construct(EditRepository $repo, BranchInfo $branch, EjournalController $ejournalController)
    {
        $this->ejournalController = $ejournalController;
        $this->branch = $branch;
        $this->repo = $repo;
    }

    public function editpart2(string $mode, Request $request)
    {
        $this->orderRecord = $this->ejournalController->getOrderRecord();
        $allPossibleTeamMembers = $this->repo->getAllPossibleTeamMembersArray($this->orderRecord->branchId);
        $allPossibleTeamEngineer = $this->repo->getAllPossibleTeamEngineerArray($this->orderRecord->branchId);
        $this->orderRecord->worksSpecsId = (int)$request->input('directions');
        $this->orderRecord->substationId = (int)$request->input('substationDialer');
        $this->orderRecord->lineId = (int)$request->input('selectLine');
        $this->orderRecord->unitId = (int)$request->input('district');
        $this->orderRecord->wardenId = (int)$request->input('warden');
        $this->orderRecord->adjusterId = (int)$request->input('adjuster');
        $this->orderRecord->brigadeMembersIds = $request->input('write_to_db_brigade');
        $this->orderRecord->brigadeEngineerIds = $request->input('write_to_db_engineers');
        $workslist = trim($request->get('workslist'));
        $pos = strpos($workslist, ' виконати '); // розділяємо текст на частини: об'єкти та робота з поля введення workslist по слову  ' виконати '
        $this->orderRecord->objects = substr($workslist, 0, $pos);
        $this->orderRecord->tasks = substr($workslist, $pos + 1);
        $this->orderRecord->workBegin = date("Y-m-d H:i", strtotime($request->input('datetime_work_begin')));
        $this->orderRecord->workEnd = date("Y-m-d H:i", strtotime($request->input('datetime_work_end')));
        $this->setOrderRecord($this->orderRecord);
        session(['orderRecord' => $this->orderRecord]); //  на всякий випадок

        $countRowPreparations = 0;
        $maxIdPreparation = 0;

        if ($mode == 'reedit') {  // якщо reedit, дані берем не з бази, а з session
            $this->preparations = session('preparations');
            if (!empty($this->preparations)) {
                $maxIdPreparation = max(array_column($this->preparations, 'id'));
                $countRowPreparations = count($this->preparations);
            }
        }
        if ($mode == 'clone') {
            $maxIdPreparation = Preparation::getMaxId($this->orderRecord->id);
            if ($maxIdPreparation > 0) {
                $this->preparations = json_decode(Preparation::getData($this->orderRecord->id), true);
                $maxIdPreparation = max(array_column($this->preparations, 'id'));
                $countRowPreparations = count($this->preparations);
            }
        }
        session(['preparations' => $this->preparations]);
        session(['mode' => $mode]);

        $substationTypeId = Substation::getTypeId($this->orderRecord->substationId);
        return view('orders.edit.editPart2', [
            'title' => '№ ' . $this->orderRecord->id . ' препарації',
            'mode' => $mode,
            'substations' => Substation::getListArray($this->orderRecord->branchId, $substationTypeId),
            'maxIdPreparation' => $maxIdPreparation,
            'countRowPreparations' => $countRowPreparations,
            'allPossibleTeamMembers' => $allPossibleTeamMembers,
            'allPossibleTeamEngineer' => $allPossibleTeamEngineer,
            'preparations' => $this->preparations,
            'orderRecord' => $this->orderRecord,
        ]);
    }
}
