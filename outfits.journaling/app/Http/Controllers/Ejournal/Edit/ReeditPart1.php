<?php

namespace App\Http\Controllers\Ejournal\Edit;

use App\Http\Controllers\Ejournal\EjournalController;
use App\Model\Ejournal\Dicts\Adjuster;
use App\Model\Ejournal\Dicts\BrigadeEngineer;
use App\Model\Ejournal\Dicts\BrigadeMember;
use App\Model\Ejournal\Dicts\Substation;
use App\Model\Ejournal\Dicts\Warden;
use App\Model\Ejournal\OrderRecordDTO;
use App\Model\User\Entity\BranchInfo;

class ReeditPart1
{

    private EjournalController $ejournalController;

    public function __construct(EditRepository $repo, BranchInfo $branch, EjournalController $ejournalController)
    {
        $this->ejournalController = $ejournalController;
        $this->branch = $branch;
        $this->repo = $repo;
    }

    public function reeditPart1(OrderRecordDTO $orderRecord)
    {
        session(['mode' => 'reedit']);
        $this->preparations_rs = session('preparations_rs');
        $this->orderRecord = session('orderRecord');
        $branch = $this->ejournalController->currentUser->userBranch;
        $wardens = Warden::where('branch_id', $branch->id)->orderBy('id')->get();
        $warden = Warden::find($this->orderRecord['warden_id']);
        $adjusters = Adjuster::where('branch_id', $branch->id)->orderBy('id')->get();
        $adjuster = Adjuster::find($this->orderRecord['adjuster_id']);

        $brig_m_arr = BrigadeMember::where('branch_id', $branch->id)->orderBy('id')->get();   // масив усіх можливих членів бригади
        $brig_e_arr = BrigadeEngineer::where('branch_id', $branch->id)->orderBy('id')->get(); // масив усіх можливих машиністів бригади
        $this->substation_type_id = Substation::find($this->orderRecord['substation_id'])->type_id;
        $brigadeText = '';
        if (isset($this->orderRecord['brigade_m'])) {
            $brigadeText = BrigadeMember::find(explode(",", $this->orderRecord['brigade_m']));
        }
        $engineersText = '';
        if (isset($this->orderRecord['brigade_e'])) {
            $engineersText = BrigadeEngineer::find(explode(",", $this->orderRecord['brigade_e']));
        }
        return view('orders.edit', [
            'mode' => 'reedit',
            'order_id' => $orderId,
            'title' => ' клон № ' . $orderId,
            'branch' => $branch,
            'unit_id' => $this->orderRecord['unit_id'],
            'unit_txt' => Unit::find($this->orderRecord['unit_id'])->body,
            'units' => Unit::where('branch_id', $branch->id)->orderBy('id')->get(),
            'wardens' => $wardens,
            'warden_id' => $this->orderRecord['warden_id'],
            'warden_txt' => $warden->body . ', ' . $warden->group,
            'adjusters' => $adjusters,
            'adjuster_id' => $this->orderRecord['adjuster_id'],
            'adjuster_txt' => $adjuster->body . ', ' . $adjuster->group,
            'brigade_m' => $this->orderRecord['brigade_m'], // id-шники через кому
            'brig_m_arr' => $brig_m_arr,
            'brigade_e' => $this->orderRecord['brigade_e'], // id-шники через кому
            'brig_e_arr' => $brig_e_arr,
            'brigade_txt' => $brigadeText,
            'engineers_txt' => $engineersText,
            'countbrigade' => count(explode(",", $this->orderRecord['brigade_m'])) + count(explode(",", $this->orderRecord['brigade_m'])),
            'substation_id' => $this->orderRecord['substation_id'],
            'substation_txt' => Substation::find($this->orderRecord['substation_id'])->body,
            'substation_type_id' => $this->substation_type_id,
            'substations' => Substation::select('id', 'body')->where('branch_id', $this->orderRecord['branch_id'])->where('type_id', $this->substation_type_id)->orderBy('body')->get(),
            'line_id' => $this->orderRecord['line_id'],
            'sep_instrs' => $this->orderRecord['sep_instrs'],
            'order_creator' => $this->orderRecord['order_creator'],
            'order_longer' => $this->orderRecord['order_longer'],
            'under_voltage' => $this->orderRecord['under_voltage'],
            'workspecs' => \App\Model\Ejournal\Dicts\WorksSpec::worksSpecCollect(),
            'workspecs_id' => $this->orderRecord['workspecs_id'],
            'workslist' => $this->orderRecord['objects'] . ' виконати ' . $this->orderRecord['tasks'],
            'orderRecord' => $this->orderRecord,
            'preparations_rs' => $this->preparations_rs,
            'measures_rs' => $this->measures_rs
        ]);
    }
}
