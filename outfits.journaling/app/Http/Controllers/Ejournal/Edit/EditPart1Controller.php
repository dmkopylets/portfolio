<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ejournal\Edit;

use App\Http\Controllers\Ejournal\BaseController;
use App\Model\Ejournal\Dicts\Unit;
use App\Model\Ejournal\OrderRecordDTO;
use App\Model\User\Entity\BranchInfo;


class EditPart1Controller extends BaseController
{
    protected BranchInfo $branch;
    private EditRepository $repo;

    public function __construct(EditRepository $repo, BranchInfo $branch)
    {
        $this->branch = $branch;
        $this->repo = $repo;
    }

    public function editpart1(OrderRecordDTO $orderRecord, string $mode)
    {
        $wardens = $this->repo->getWardens($orderRecord->branchId);
        $adjusters = $this->repo->getAdjusters($orderRecord->branchId);
        $allPossibleTeamMembers = $this->repo->getAllPossibleTeamMembersArray($orderRecord->branchId);
        $allPossibleTeamEngineer = $this->repo->getAllPossibleTeamEngineerArray($orderRecord->branchId);
        if (isset($orderRecord->brigadeMembersIds)) $brigadeText = $this->repo->fetchBrigadeMembers($orderRecord->brigadeMembersIds);
        if (isset($orderRecord->brigadeEngineerIds)) $engineersText = $this->repo->fetchBrigadeEngineer($orderRecord->brigadeEngineerIds);
        $countBrigade = count(explode(",", $orderRecord->brigadeMembersIds)) + count(explode(",", $orderRecord->brigadeEngineerIds));

        $teamList = '';
        if ($mode !== 'create') {
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
        session(['orderRecord' => $orderRecord]);
        $this->repo->setMode($mode);

        return view('orders.edit.editPart1', [
            'mode' => $mode,
            'title' => '№ ' . $orderRecord->id,
            'branch' => $this->getBranch(),
            'allPossibleTeamMembers' => $allPossibleTeamMembers,
            'allPossibleTeamEngineer' => $allPossibleTeamEngineer,
            'units' => Unit::where('branch_id', $orderRecord->branchId)->orderBy('id')->get(),
            'wardens' => $wardens,
            'adjusters' => $adjusters,
            'countbrigade' => $countBrigade,
            'worksSpecsId' => $orderRecord->worksSpecsId,
            'teamList' => $teamList,
            'orderRecord' => $orderRecord,
            'editRopository' => $this->repo,
        ]);
    }
}
