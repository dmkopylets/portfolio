<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ejournal\Edit;

use App\Http\Controllers\Ejournal\BaseController;
use App\Model\Ejournal\OrderRecordDTO;
use App\Model\User\Entity\BranchInfo;

class ReEditPart1 extends BaseController
{
private  EditPart1Controller $editPart1Controller;
    public function __construct(EditRepository $repo, BranchInfo $branch)
    {
        parent::__construct();
        $this->branch = $branch;
        $this->repo = $repo;
        $this->editPart1Controller = new EditPart1Controller($this->repo, $this->branch);
    }

    public function reEditPart1(OrderRecordDTO $orderRecord)
    {
        $this->repo->getOrderRecord();
        $orderRecord->editMode = 'reedit';
        $this->repo->setOrderRecord($orderRecord);
        return $this->editPart1Controller->editPart1($orderRecord);
    }
}
