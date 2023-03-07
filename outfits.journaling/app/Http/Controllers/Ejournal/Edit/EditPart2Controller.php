<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ejournal\Edit;

use App\Http\Controllers\Ejournal\BaseController;
use App\Model\Ejournal\OrderRecordDTO;
use App\Model\User\Entity\BranchInfo;
use App\Http\Requests\EditOrderPart1Request;
use Illuminate\Support\Facades\Validator;

class EditPart2Controller
{
    protected BranchInfo $branch;
    private EditRepository $repo;
    private EditPart1Controller $editPart1Controller;
    private OrderRecordDTO $orderRecord;

    public function __construct(EditRepository $repo)
    {
        $this->repo = $repo;
    }

    public function editpart2(OrderRecordDTO $orderRecord, EditOrderPart1Request $request)
    {
        session()->forget('error');
        $orderRecord->worksSpecsId = (int)$request->input('directions');
        $orderRecord->substationId = (int)$request->input('substationDialer');
        $orderRecord->lineId = (int)$request->input('selectLine');
        $orderRecord->unitId = (int)$request->input('district');
        $orderRecord->wardenId = (int)$request->input('warden');
        $orderRecord->adjusterId = (int)$request->input('adjuster');
        $orderRecord->workBegin = date("Y-m-d H:i", strtotime($request->input('datetime_work_begin')));
        $orderRecord->workEnd = date("Y-m-d H:i", strtotime($request->input('datetime_work_end')));
        $orderRecord->brigadeEngineerIds = (string)$request->input('write_to_db_engineers');
        $v = Validator::make($request->all(), $request->rules(), $request->messages());
        if (!$v->fails()) {
            $validatedData = $request->validated();
            $preparations = [];
            $countRowPreparations = 0;
            $maxIdPreparation = 0;
            $orderRecord->brigadeMembersIds = $validatedData['write_to_db_brigade'];
            $workslist = trim($validatedData['workslist']);
            $pos = strpos($workslist, ' виконати '); // розділяємо текст на частини: об'єкти та робота з поля введення workslist по слову  ' виконати '
            $orderRecord->objects = substr($workslist, 0, $pos);
            $orderRecord->tasks = substr($workslist, $pos + 1);

            if ($orderRecord->editMode == 'reedit') {  // якщо reedit, основні дані для цієї форми берем не з бази, а з session
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

            $this->repo->setOrderRecord($orderRecord);
            $substationTypeId = $this->repo->getSubstationTypeId($orderRecord->substationId);
            return view('orders.edit.editPart2', [
                'title' => ($orderRecord->id == 0) ? 'новий наряд' : 'клонуємо наряд № ' . $orderRecord->id,
                'mode' => $orderRecord->editMode,
                'substations' => $this->repo->getSubstationsList($orderRecord->branchId, $substationTypeId),
                'maxIdPreparation' => $maxIdPreparation,
                'countRowPreparations' => $countRowPreparations,
                'preparations' => $preparations,
                'orderRecord' => $orderRecord,
                'editRepository' => $this->repo,
            ]);
        } else {



            $orderRecord->brigadeMembersIds  = (string)$request->input('write_to_db_brigade');

            $this->repo->setOrderRecord($orderRecord);
            $errorMessage = '';
            foreach ( $v->errors()->toArray()  as $messageBag){
                $errorMessage .= $messageBag[0] . '! ';
            }
            session()->flash('error', $errorMessage);
            $editPart1Controller = new EditPart1Controller($this->repo, $this->branch);
            return $editPart1Controller->editPart1($orderRecord);



//            return view('orders.edit.editPart1', [
//                'title' => ($orderRecord->id == 0) ? 'новий наряд' : 'клонуємо наряд № ' . $orderRecord->id,
//                'branch' => $this->getBranch(),
//                'allPossibleTeamMembers' => $this->repo->getAllPossibleTeamMembersArray($orderRecord->branchId),
//                'allPossibleTeamEngineer' => $this->repo->getAllPossibleTeamEngineerArray($orderRecord->branchId),
//                'units' => $this->repo->getUnits($orderRecord->branchId),
//                'wardens' => $this->repo->getWardens($orderRecord->branchId),
//                'adjusters' => $this->repo->getAdjusters($orderRecord->branchId),
//                'countBrigade' => count(explode(",", $orderRecord->brigadeMembersIds)) + count(explode(",", $orderRecord->brigadeEngineerIds)),
//                'worksSpecsId' => $orderRecord->worksSpecsId,
//                'teamList' => $this->repo->getBrigadeText($orderRecord),
//                'orderRecord' => $orderRecord,
//                'editRepository' => $this->repo,
//            ]);
        }
    }
}


