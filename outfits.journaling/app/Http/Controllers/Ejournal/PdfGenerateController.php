<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ejournal;

use App\Http\Controllers\Ejournal\BaseController as BaseController;
use App\Model\Ejournal\Dicts\Adjuster;
use App\Model\Ejournal\Dicts\BrigadeEngineer;
use App\Model\Ejournal\Dicts\BrigadeMember;
use App\Model\Ejournal\Dicts\StationType;
use App\Model\Ejournal\Dicts\Substation;
use App\Model\Ejournal\Dicts\Unit;
use App\Model\Ejournal\Dicts\Warden;
use App\Model\Ejournal\Measure;
use App\Model\Ejournal\Order;
use App\Model\Ejournal\Preparation;
use PDF;

class PdfGenerateController extends BaseController
{
    public function pdf(Order $order)
    {
//        if (isset($this->naryadRecord['brig_m_ch'])) {
//            $brigadeTxt = BrigadeMember::find(explode(",", $this->naryadRecord['brig_m_ch']));
//        }
//        $engineersTxt = '';
//        if (isset($this->naryadRecord['brig_e_ch'])) {
//            $engineers_txt = BrigadeEngineer::find(explode(",", $this->naryadRecord['brig_e_ch']));
//        }
        $substationTypeId = Substation::find($order->substation_id)->type_id;

        $pdf = PDF::loadView('orders.pdf', [
            'order' => $order,
            'unitName' => Unit::find($order->unit_id)->body,
            'wardenTxt' => Warden::find($order->warden_id)->body . ', ' . Warden::find($order->warden_id)->group,
            'adjusterTxt' => Adjuster::find($order->adjuster_id)->body . ', ' . Adjuster::find($order->adjuster_id)->group,
            'brigadeTxt' => BrigadeMember::find(explode(",", $order->brigade_m)),
            'engineersTxt' => BrigadeEngineer::find(explode(",", $order->brigade_e)),
            'substationTxt' => Substation::find($order->substation_id)->body,
            'substationType' => StationType::find($substationTypeId)->body,
            'branchName' => $this->currentUser->userBranch->body,
            'preparations' => Preparation::get_data($order->id),
            'measures' => Measure::get_data($order->id)
        ])->setPaper('a4', 'landscape')->setWarnings(false);

        return $pdf->stream('Order.pdf');
    }
}
