<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ejournal\Edit;

use App\Http\Controllers\Ejournal\BaseController;
use App\Http\Controllers\Ejournal\OrdersController;
use App\Model\Ejournal\OrderRecordDTO;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isNull;

class EditPart4Controller extends BaseController
{
    private EditRepository $repo;

    public function __construct(EditRepository $repo)
    {
        parent::__construct();
        $this->repo = $repo;
    }

    public function editpart4(OrderRecordDTO $orderRecord, Request $request)
    {
        $mode = ((is_string(session('mode'))) ? session('mode') : ($orderRecord->id == 0)) ? 'create' : 'clone';
        $orderRecord->separateInstructions = trim($request->get('sep_instrs_txt'));
        $orderRecord->orderDate = date("Y-m-d H:i", strtotime(trim($request->datetime_order_created)));
        $orderRecord->orderCreator = trim($request->inp_order_creator);

        $orderRecord->orderLonger = '';
        if (!isNull($request->datetime_order_longed)) {
            $orderRecord->orderLongTo = date("Y-m-d H:i", strtotime(trim($request->datetime_order_longed)));
            $orderRecord->orderLonger = trim($request->inp_order_longer);
        }

        session(['orderRecord' => $orderRecord]);


        /* займемося ровсетом measures - набором рядочків таблиці measures (підготовчих заходів), що мають прив`язку до номеру клонованого наряду  */
        $countMeasureRows = 0;
        $maxIdMeasure = 0;
        $this->measures = array();
        if ($mode == 'reedit') {  // якщо reedit, дані берем не з бази, а з session
            $this->measures = session('measures');
            if (!empty($this->measures)) {
                $maxIdMeasure = max(array_column($this->measures, 'id'));
                $countMeasureRows = count($this->measures);
            }
        }
        if ($mode === 'clone') {
            $maxIdMeasureTmp = $this->repo->getMeasuresMaxId($orderRecord->id);
            if ($maxIdMeasureTmp > 0) {
                $this->measures = $this->repo->getMeasuresFromDB($orderRecord->id)->toArray();
                $maxIdMeasure = max(array_column($this->measures, 'id'));
                $countMeasureRows = count($this->measures);
            }
        }

        session(['measures' => $this->measures]);

        return view('orders.edit.editPart4', [
            'title' => '№ ' . $orderRecord->id . ' підготовка2',
            'mode' => $mode,
            'maxIdMeasure' => $maxIdMeasure,
            'count_meas_row' => $countMeasureRows,
            'orderRecord' => $orderRecord->toArray(),
            'measures' => $this->measures
        ]);
    }
}
