<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ejournal\Edit;

use App\Http\Controllers\Ejournal\BaseController;
use App\Model\Ejournal\OrderRecordDTO;
use App\Model\User\Entity\BranchInfo;
use Illuminate\Http\Request;

class CreateOrder
{
    private EditRepository $repo;
    private OrderRecordDTO $orderRecord;
    private BranchInfo $branch;
    public function __construct()
    {
        $baseController = new BaseController();
        $this->branch = $baseController->currentUser->userBranch;
        $this->repo = new EditRepository();
    }
    public function createOrder(Request $request): \Illuminate\View\View
    {
        $this->orderRecord = $this->repo->initOrderRecord($this->branch->id);
        $this->orderRecord->worksSpecsId = (int)$request->input('direction');
        $this->orderRecord->editMode = 'create';
        session(['orderRecord' => $this->orderRecord]);
        $editPart1Controller = new EditPart1Controller($this->repo, $this->branch);
        return $editPart1Controller->editPart1($this->orderRecord);
    }
}
