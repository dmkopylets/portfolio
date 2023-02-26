<?php

namespace App\Http\Controllers\Ejournal\Edit;

use App\Model\User\Entity\BranchInfo;
use Illuminate\Http\Request;

class ReEditPart2
{

    public function __construct(EditRepository $repo, BranchInfo $branch)
    {
        $this->branch = $branch;
        $this->repo = $repo;
    }

    public function reedit2($orderId, Request $request)
    {
        session(['mode' => 'reedit']);
        session(['measures_rs' => $this->measures_rs]);
        $this->preparations_rs = session('preparations_rs');

        if (empty($this->preparations_rs)) {
            $count_prepr_row = 0;
            $maxIdpreparation = 0;
        } else {
            $maxIdpreparation = max(array_column($this->preparations_rs, 'id'));
            $count_prepr_row = count($this->preparations_rs);
        }

        $this->ejournalController->orderRecord = session('orderRecord');

        $this->orderRecord['sep_instrs'] = trim($request->get('sep_instrs_txt'));
        $this->orderRecord['order_date'] = date("Y-m-d H:i", strtotime(trim($request->datetime_order_created)));
        $this->orderRecord['order_creator'] = trim($request->inp_order_creator);
        $this->orderRecord['order_longto'] = date("Y-m-d H:i", strtotime(trim($request->datetime_order_longed)));
        $this->orderRecord['order_longer'] = trim($request->inp_order_longer);
        $this->orderRecord['under_voltage'] = trim($request->get('under_voltage'));

        session()->forget('orderRecord');
        session(['orderRecord' => $this->ejournalController->orderRecord]);

        return view('orders.editPart2', [
            'mode' => 'reedit',
            'title' => ' клон № ' . $orderId,
            'branch_id' => $this->ejournalController->orderRecord['branch_id'],
            'substation_id' => $this->ejournalController->orderRecord['substation_id'],
            'substation_txt' => Substation::find($this->ejournalController->orderRecord['substation_id'])->body,
            'substation_type_id' => $this->ejournalController->orderRecord['substation_type_id'],
            'substations' => Substation::select('id', 'body')->where('branch_id', $this->ejournalController->orderRecord['branch_id'])->where('type_id', $this->ejournalController->orderRecord['substation_type_id'])->orderBy('body')->get(),
            'count_prepr_row' => $count_prepr_row,
            'maxIdpreparation' => $maxIdpreparation,
            'preparations_rs' => $this->preparations_rs,
            'orderRecord' => $this->ejournalController->orderRecord
        ]);
    }
}
