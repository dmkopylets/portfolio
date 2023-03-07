<?php

namespace App\Http\Controllers\Ejournal\Edit;

use App\Http\Controllers\Ejournal\BaseController as BaseController;
use Illuminate\Http\Request;

class ReEditPart2 extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->repo = new EditRepository();
    }

    public function reEditPart2($orderId, Request $request)
    {
        $orderRecord = session('orderRecord');
        $orderRecord->editMode = 'reedit';
        $preparations = session('preparations');

        $maxIdPreparation = 0;
        $countRowPreparations = 0;

        if (!empty($preparations)) {
            $maxIdPreparation = max(array_column($preparations, 'id'));
            $countRowPreparations = count($preparations);
        }

        $orderRecord->separateInstructions = trim($request->get('sep_instrs_txt'));
        $orderRecord->orderDate = date("Y-m-d H:i", strtotime(trim($request->datetime_order_created)));
        $orderRecord->orderCreator = trim($request->inp_order_creator);
        $orderRecord->orderLongTo = date("Y-m-d H:i", strtotime(trim($request->datetime_order_longed)));
        $orderRecord->orderLonger = trim($request->inp_order_longer);
        $orderRecord->underVoltage = trim($request->get('under_voltage'));
        $this->repo->setOrderRecord($orderRecord);
        $substationTypeId = $this->repo->getSubstationTypeId($orderRecord->substationId);

        return view('orders.edit.editPart2', [
            'title' => ($orderRecord->id == 0) ? 'новий наряд' : 'клонуємо наряд № ' . $orderRecord->id,
            'mode' => $orderRecord->editMode,
            'substations' => $this->repo->getSubstationsList($orderRecord->branchId, $substationTypeId),
            'maxIdPreparation' => $maxIdPreparation,
            'countRowPreparations' => $countRowPreparations,
            'preparations' => $preparations,
            'orderRecord' => $orderRecord,
            'editRepository' => $this->repo,
        ]);
    }
}
