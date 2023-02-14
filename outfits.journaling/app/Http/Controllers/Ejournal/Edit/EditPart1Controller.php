<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ejournal\Edit;

use App\Http\Controllers\Ejournal\BaseController;
use App\Model\Ejournal\Dicts\Adjuster;
use App\Model\Ejournal\Dicts\BrigadeEngineer;
use App\Model\Ejournal\Dicts\BrigadeMember;
use App\Model\Ejournal\Dicts\StationType;
use App\Model\Ejournal\Dicts\Substation;
use App\Model\Ejournal\Dicts\Warden;
use App\Model\Ejournal\Dicts\Unit;
use App\Model\Ejournal\Order;

class EditPart1Controller extends BaseController
{
    private EditRepository $repo;

    public function __construct(EditRepository $repo)
    {
        $this->repo = $repo;
    }

    public function editpart1(int $orderId)
    {
        $branch = $this->getBranch();
        $orderRecord = Order::find($orderId);
        $brig_m_arr = BrigadeMember::where('branch_id', $this->getBranch()->id)->orderBy('id')->get();   // масив усіх можливих членів бригади
        $brig_e_arr = BrigadeEngineer::where('branch_id', $this->getBranch()->id)->orderBy('id')->get(); // масив усіх можливих машиністів бригади
        $wardens = Warden::where('branch_id', $branch->id)->orderBy('id')->get();
        $warden = Warden::find($orderRecord->warden_id);
        $adjusters = Adjuster::where('branch_id', $this->getBranch()->id)->orderBy('id')->get();
        $adjuster = Adjuster::find($orderRecord->adjuster_id);
        $brigadeText = '';
        if (isset($orderRecord->brigade_m)) {
            $brigadeText = BrigadeMember::find(explode(",", $orderRecord->brigade_m));
        }
        $engineersText = '';
        if (isset($this->rorderRecord->brigade_e)) {
            $engineersText = BrigadeEngineer::find(explode(",", $orderRecord->brigade_e));
        }
        $countbrigade = count(explode(",", $orderRecord->brigade_m)) + count(explode(",", $orderRecord->brigade_e));
        $substation = Substation::find($orderRecord->substation_id);
        $substation_id = $substation->id;
        $substation_txt = $substation->body;
        $substation_type_id = $substation->type_id;
        $substation_type = StationType::find($substation_type_id)->body;
        $substations = $this->repo->getSubstationsList($branch->id, $substation_type_id);   // однотипні підстанції підрозділу

        return view('orders.edit.editPart1', [
            'mode' => 'clone',
            'title' => '№ ' . $orderId,
            'branch' => $branch,
            'brig_m_arr' => $brig_m_arr,
            'brig_e_arr' => $brig_e_arr,
            'units' => Unit::where('branch_id', $branch->id)->orderBy('id')->get(),
            'wardens' => $wardens,
            'adjusters' => $adjusters,
            'countbrigade' => $countbrigade,
            'substations' => $substations,
            'worksSpecsId' => $orderRecord->works_spec_id,
            'workslist' => $orderRecord->objects . ' виконати ' . $orderRecord->tasks,
            'brigade_txt' => $brigadeText,
            'engineers_txt' => $engineersText,
            'substation_txt' => $substation_txt,
            'orderRecord' => $orderRecord,
        ]);
    }
}
