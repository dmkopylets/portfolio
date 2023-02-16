<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ejournal\Edit;

use App\Http\Controllers\Ejournal\BaseController;
use App\Model\Ejournal\Dicts\Adjuster;
use App\Model\Ejournal\Dicts\StationType;
use App\Model\Ejournal\Dicts\Substation;
use App\Model\Ejournal\Dicts\Warden;
use App\Model\Ejournal\Dicts\Unit;
use App\Model\Ejournal\Order;
use App\Model\Ejournal\OrderRecordDTO;
use App\Model\User\Entity\BranchInfo;
use App\Model\User\Entity\UserRepository;

class EditPart1Controller extends BaseController
{
    protected BranchInfo $branch;

    public function __construct(public UserRepository $userRepository, private EditRepository $repo)
    {
        parent::__construct($userRepository);
        $this->branch = $this->currentUser->userBranch;
        $this->repo = $repo;
    }

    public function editpart1(int $orderId)
    {
        $mode = session('mode');
        if (!isset($mode)){
            $mode = 'clone';
        }
        $branch = $this->branch;
        $orderFinded = Order::find($orderId);
        $orderRecord = new OrderRecordDTO();
        $orderRecord->id = $orderFinded->id;
        $orderRecord->branchId = $orderFinded->branch_id;
        $orderRecord->unitId = $orderFinded->unit_id;
        $orderRecord->wardenId = $orderFinded->warden_id;
        $orderRecord->adjusterId = $orderFinded->adjuster_id;
        $orderRecord->brigadeMembersIds = $orderFinded->brigade_m;
        $orderRecord->brigadeEngineerIds = $orderFinded->brigade_e;
        $orderRecord->substationId = $orderFinded->substation_id;
        $orderRecord->worksSpecsId = $orderFinded->works_spec_id;
        $orderRecord->objects = $orderFinded->objects;
        $orderRecord->tasks = $orderFinded->tasks;
        $orderRecord->lineId = $orderFinded->line_id;
        $orderRecord->workBegin = $orderFinded->w_begin;
        $orderRecord->workEnd = $orderFinded->w_end;
        $orderRecord->separateInstructions = $orderFinded->sep_instrs;
        $orderRecord->orderDate = $orderFinded->order_date;
        $orderRecord->orderCreator = $orderFinded->order_creator;
        $orderRecord->orderLongTo = $orderFinded->order_longto;
        $orderRecord->orderLonger = $orderFinded->order_longer;
        $orderRecord->underVoltage = $orderFinded->under_voltage;

        $allPossibleTeamMembers = $this->repo->getAllPossibleTeamMembersArray($branch->id);
        $allPossibleTeamEngineer = $this->repo->getAllPossibleTeamEngineerArray($branch->id);

        $wardens = Warden::where('branch_id', $branch->id)->orderBy('id')->get();
        $adjusters = Adjuster::where('branch_id', $this->getBranch()->id)->orderBy('id')->get();
        if (isset($orderRecord->brigadeMembersIds)) $brigadeText = $this->repo->fetchBrigadeMembers($orderRecord->brigadeMembersIds);
        if (isset($orderRecord->brigadeEngineerIds)) $engineersText = $this->repo->fetchBrigadeEngineer($orderRecord->brigadeEngineerIds);
        $countbrigade = count(explode(",", $orderRecord->brigadeMembersIds)) + count(explode(",", $orderRecord->brigadeEngineerIds));
        $substation = Substation::find($orderRecord->substationId);

        $substationTxt = $substation->body;
        $substation_type_id = $substation->type_id;
        $substation_type = StationType::find($substation_type_id)->body;
        $substations = $this->repo->getSubstationsList($branch->id, $substation_type_id);   // однотипні підстанції підрозділу
        $this->setOrderRecord($orderRecord);

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


        return view('orders.edit.editPart1', [
            'mode' => $mode,
            'title' => '№ ' . $orderId,
            'branch' => $branch,
            'allPossibleTeamMembers' => $allPossibleTeamMembers,
            'allPossibleTeamEngineer' => $allPossibleTeamEngineer,
            'units' => Unit::where('branch_id', $branch->id)->orderBy('id')->get(),
            'wardens' => $wardens,
            'adjusters' => $adjusters,
            'countbrigade' => $countbrigade,
            'substations' => $substations,
            'worksSpecsId' => $orderRecord->worksSpecsId,
            'workslist' => $orderRecord->objects . ' виконати ' . $orderRecord->tasks,
            'teamList' => $teamList,
//            'engineersText' => $engineersText,
            'substation_txt' => $substationTxt,
            'orderRecord' => $orderRecord,
        ]);
    }
}
