<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ejournal\Edit;

use App\Http\Controllers\Ejournal\BaseController;
use App\Model\Ejournal\OrderRecordDTO;
use App\Model\User\Entity\BranchInfo;

class EditPart1Controller extends BaseController
{
    protected BranchInfo $branch;
    private EditRepository $repo;

    public function __construct(EditRepository $repo, BranchInfo $branch)
    {
        $this->repo = $repo;
        $this->branch = $branch;
    }

    public function editPart1(OrderRecordDTO $orderRecord)
    {
        $teamList = '';
        $countBrigade = 0;
        if ($orderRecord->editMode !== 'create') {
            if (isset($orderRecord->brigadeMembersIds)) $brigadeText = $this->repo->fetchBrigadeMembers($orderRecord->brigadeMembersIds);
            if (isset($orderRecord->brigadeEngineerIds)) $engineersText = $this->repo->fetchBrigadeEngineer($orderRecord->brigadeEngineerIds);
            $countBrigade = count(explode(",", $orderRecord->brigadeMembersIds)) + count(explode(",", $orderRecord->brigadeEngineerIds));
            if (isset($brigadeText)) {
                foreach ($brigadeText as $txtPart1) {
                    $teamList = $teamList . $txtPart1['body'] . ' ' . $txtPart1['group'] . ', ';
                }
            }
            if (isset($engineersText)) {
                foreach ($engineersText as $txtPart2) {
                    $teamList = $teamList . $txtPart2['specialization'] . ' ' . $txtPart2['body'] . ' ' . $txtPart2['group'] . ', ';
                }
            }
        }

        return view('orders.edit.editPart1', [
            'title' => ($orderRecord->id == 0) ? 'новий наряд' : 'клонуємо наряд № ' . $orderRecord->id,
            'branch' => $this->getBranch(),
            'allPossibleTeamMembers' => $this->repo->getAllPossibleTeamMembersArray($orderRecord->branchId),
            'allPossibleTeamEngineer' => $this->repo->getAllPossibleTeamEngineerArray($orderRecord->branchId),
            'units' => $this->repo->getUnits($orderRecord->branchId),
            'wardens' => $this->repo->getWardens($orderRecord->branchId),
            'adjusters' => $this->repo->getAdjusters($orderRecord->branchId),
            'countBrigade' => $countBrigade,
            'worksSpecsId' => $orderRecord->worksSpecsId,
            'teamList' => $teamList,
            'orderRecord' => $orderRecord,
            'editRepository' => $this->repo,
        ]);
    }
}
