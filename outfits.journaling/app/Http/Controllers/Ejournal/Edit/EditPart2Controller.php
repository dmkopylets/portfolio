<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ejournal\Edit;

use App\Http\Controllers\Ejournal\BaseController;
use App\Model\Ejournal\OrderRecordDTO;
use App\Model\User\Entity\BranchInfo;
use Illuminate\Http\Request;

class EditPart2Controller extends BaseController
{
    protected BranchInfo $branch;
    private EditRepository $repo;

    public function __construct(EditRepository $repo)
    {
        $this->repo = $repo;
    }

    public function editpart2(OrderRecordDTO $orderRecord, Request $request)
    {
        $preparations = [];
        $countRowPreparations = 0;
        $maxIdPreparation = 0;
        $orderRecord->worksSpecsId = (int)$request->input('directions');
        $orderRecord->substationId = (int)$request->input('substationDialer');
        $orderRecord->lineId = (int)$request->input('selectLine');
        $orderRecord->unitId = (int)$request->input('district');
        $orderRecord->wardenId = (int)$request->input('warden');
        $orderRecord->adjusterId = (int)$request->input('adjuster');
        $orderRecord->brigadeMembersIds = $request->input('write_to_db_brigade');
        $orderRecord->brigadeEngineerIds = $request->input('write_to_db_engineers');
        $workslist = trim($request->get('workslist'));
        $pos = strpos($workslist, ' виконати '); // розділяємо текст на частини: об'єкти та робота з поля введення workslist по слову  ' виконати '
        $orderRecord->objects = substr($workslist, 0, $pos);
        $orderRecord->tasks = substr($workslist, $pos + 1);
        $orderRecord->workBegin = date("Y-m-d H:i", strtotime($request->input('datetime_work_begin')));
        $orderRecord->workEnd = date("Y-m-d H:i", strtotime($request->input('datetime_work_end')));
        $this->repo->setOrderRecord($orderRecord);

        if ($orderRecord->editMode == 'reedit') {  // якщо reedit, дані берем не з бази, а з session
            $preparations = session('preparations');
            if (!empty($preparations)) {
                $maxIdPreparation = max(array_column($preparations, 'id'));
                $countRowPreparations = count($preparations);
            }
        }
        if ($orderRecord->editMode == 'clone') {
            $maxIdPreparation = $this->repo->getPreparationMaxId($orderRecord->id);
            if ($maxIdPreparation > 0) {
                $preparations = $this->repo->getPreparationsFromDB($orderRecord->id);
                $countRowPreparations = count($preparations);
            }
        }

        session(['preparations' => $preparations]);

        $substationTypeId = $this->repo->getSubstationTypeId($orderRecord->substationId);
        return view('orders.edit.editPart2', [
            'title' => '№ ' . $orderRecord->id . ' препарації',
            'mode' => $orderRecord->editMode,
            'substations' => $this->repo->getSubstationsList($orderRecord->branchId, $substationTypeId),
            'maxIdPreparation' => $maxIdPreparation,
            'countRowPreparations' => $countRowPreparations,
            'allPossibleTeamMembers' => $this->repo->getAllPossibleTeamMembersArray($orderRecord->branchId),
            'allPossibleTeamEngineer' => $this->repo->getAllPossibleTeamEngineerArray($orderRecord->branchId),
            'preparations' => $preparations,
            'orderRecord' => $orderRecord,
            'editRepository' => $this->repo,
        ]);
    }
}
