<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ejournal\Edit;

use App\Http\Controllers\Ejournal\OrdersController;
use App\Model\Ejournal\Dicts\Adjuster;
use App\Model\Ejournal\Dicts\BrigadeEngineer;
use App\Model\Ejournal\Dicts\BrigadeMember;
use App\Model\Ejournal\Dicts\Substation;
use App\Model\Ejournal\Dicts\Warden;
use App\Model\Ejournal\OrderRecordDTO;
use App\Model\User\Entity\BranchInfo;

class ReEditPart1
{

    public function __construct(EditRepository $repo, BranchInfo $branch)
    {
        $this->branch = $branch;
        $this->repo = $repo;
    }

    public function reEditPart1(OrderRecordDTO $orderRecord)
    {
        $orderRecord->editMode = 'reedit';
        $this->repo->setOrderRecord($orderRecord);
        $editPart1Controller = new EditPart1Controller($this->repo, $this->branch);
        return $editPart1Controller->editPart1($orderRecord);
    }
}
