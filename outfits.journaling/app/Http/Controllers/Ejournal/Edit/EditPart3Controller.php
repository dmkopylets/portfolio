<?php

namespace App\Http\Controllers\Ejournal\Edit;

use App\Model\Ejournal\OrderRecordDTO;
use Illuminate\Http\Request;

class EditPart3Controller
{
    public function editpart3(OrderRecordDTO $orderRecord, Request $request)
    {
        return view('orders.edit.editPart3', [
            'title' => ($orderRecord->id == 0) ? 'новий наряд' : 'клонуємо наряд № ' . $orderRecord->id,
            'orderRecord' => $orderRecord,
        ]);
    }
}
