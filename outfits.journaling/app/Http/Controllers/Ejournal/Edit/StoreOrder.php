<?php

namespace App\Http\Controllers\Ejournal\Edit;

use App\Http\Controllers\Ejournal\EjournalController;
use App\Model\Ejournal\Order;
use App\Model\Ejournal\OrderRecordDTO;
use App\Model\User\Entity\BranchInfo;
use Illuminate\Http\Request;
use Redirect;

class StoreOrder
{
    public function __construct(EditRepository $repo, BranchInfo $branch, EjournalController $ejournalController)
    {
        $this->ejournalController = $ejournalController;
        $this->branch = $branch;
        $this->repo = $repo;
    }
    public function store(OrderRecordDTO $orderRecord, Request $request)
    {
        //$orderStored = $request->session()->get('orderRecord'); //$orderStored = session('orderRecord'); // можна і так
        //$orderRecord->id = Order::max('id') + 1;

        $orderRecord->underVoltage = trim($request->get('under_voltage_txt'));
        $request->flash();


        $newOrder = $this->repo->toModel($orderRecord);
        $newOrder->save();



        $preparations_rs = session('preparations_rs');
        $count_prepr_row = count($preparations_rs);
        if ($count_prepr_row > 0) {
            foreach ($preparations_rs as $prRow) {
                $preparationsDBRecord = new \App\Model\Ejournal\Preparation;
                $preparationsDBRecord->naryad_id = $this->order->id;
                $preparationsDBRecord->targetObj = $prRow['targetObj'];
                $preparationsDBRecord->body = $prRow['body'];
                $preparationsDBRecord->save;
            }
        }
        $meashures_rs = session('meashures_rs');
        $count_meashures_row = count($meashures_rs);
        if ($count_meashures_row > 0) {
            foreach ($meashures_rs as $msRow) {
                $meashuresDBRecord = new \App\Model\Ejournal\Measure();
                $meashuresDBRecord->naryad_id = $this->order->id;
                $meashuresDBRecord->licensor = $msRow['licensor'];
                $meashuresDBRecord->lic_date = $msRow['lic_date'];
                $meashuresDBRecord->save;
            }
        }
        return Redirect::to('orders')->with('success', 'Наряд додано!');
    }
}
