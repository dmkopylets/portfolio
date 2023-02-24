<?php

namespace App\Http\Controllers\Ejournal\Edit;

use App\Model\Ejournal\OrderRecordDTO;
use Illuminate\Http\Request;

class EditPart5Controller
{

    public function editpart5(OrderRecordDTO $orderRecord, Request $request)
    {
        $mode = ((is_string(session('mode'))) ? session('mode') : ($orderRecord->id == 0)) ? 'create' : 'clone';
        $this->orderRecord = session('orderRecord');
        return view('orders.edit.editPart5', [
            'title' => '№ ' . $orderRecord->id . ' завершення',
            'orderRecord' => $orderRecord,
            'mode' => $mode,
        ]);
    }
}
