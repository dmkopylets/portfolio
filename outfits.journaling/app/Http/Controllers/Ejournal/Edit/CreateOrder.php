<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ejournal\Edit;

use App\Model\User\Entity\BranchInfo;
use Illuminate\Http\Request;

class CreateOrder
{
    public function __construct(EditRepository $repo, BranchInfo $branch)
    {
        $this->branch = $branch;
        $this->repo = $repo;
    }

    /**
     *   !! створюєно НОВИЙ наряд
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $worksSpecsId = (int)$request->input('direction');
        $newOrder = $this->repo->initOrderRecord($this->branch->id, $worksSpecsId);
        $newOrder->editMode = 'create';
        $this->repo->setOrderRecord($newOrder);
        $editPart1Controller = new EditPart1Controller($this->repo, $this->branch);

        return $editPart1Controller->editPart1($newOrder);

    }
}
