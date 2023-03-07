<?php

namespace App\Http\Controllers\Ejournal\Edit;

use App\Http\Controllers\Ejournal\BaseController as BaseController;
use Illuminate\Http\Request;

class ReEditPart4 extends BaseController
{

    public function reEditPart4(Request $request)
    {
        $orderRecord = session('orderRecord');
        $orderRecord->editMode = 'reedit';
        $count_meas_row = 0;
        $maxIdMeasure = 0;
        $measures = session('measures');
        if (!empty($measures_rs)) {
            $maxIdMeasure = max(array_column($measures, 'id'));
            $count_meas_row = count($measures);
        }

        $orderRecord->underVoltage = trim($request->get('under_voltage'));
        session(['orderRecord' => $orderRecord]);

        return view('orders.edit.editPart4', [
            'mode' => 'reedit',
            'title' => ($orderRecord->id == 0) ? 'новий наряд' : 'клонуємо наряд № ' . $orderRecord->id,
            'measures' => $measures,
            'maxIdMeasure' => $maxIdMeasure,
            'count_meas_row' => $count_meas_row,
            'orderRecord' => session('orderRecord')
        ]);
    }
}
