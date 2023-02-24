<?php

namespace App\Http\Controllers\Ejournal\Edit;

use App\Model\Ejournal\OrderRecordDTO;
use Illuminate\Http\Request;

class EditPart3Controller
{
    public function editpart3(OrderRecordDTO $orderRecord, Request $request)
    {
        $this->orderRecord = session('orderRecord');

        return view('orders.edit.editPart3', [
            'title' => ($orderRecord->id == 0) ? 'новий наряд' : 'клонуємо наряд № ' . $orderRecord->id,
            'mode' => ($orderRecord->id == 0) ? 'create' : 'clone',
            'orderRecord' => $orderRecord,
        ]);
    }
}
