<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ejournal\Edit;

use App\Model\Ejournal\Dicts\BrigadeEngineer;
use App\Model\Ejournal\Dicts\BrigadeMember;
use App\Model\Ejournal\Dicts\Line;
use App\Model\Ejournal\Dicts\Substation;
use App\Model\Ejournal\Order;
use App\Model\Ejournal\OrderRecordDTO;
use Illuminate\Support\Facades\DB;

class EditRepository
{
    public function getSubstationsList(int $branchId, int $substationTypeId): array
    {
        return Substation::
        select('id', 'body', 'type_id')
            ->where('branch_id', $branchId)
            ->where('type_id', $substationTypeId)
            ->orderBy('type_id', 'asc')
            ->orderBy('body', 'asc')
            ->get()
            ->toArray();
    }

    public function getLinesList(int $branchId, int $substationId): array
    {
        return Line::
        select('line_id')
            ->where('branch_id', $branchId)
            ->where('substation_id', $substationId)
            ->orderBy('line_id', 'asc')
            ->get()
            ->toArray();
    }

    public function fetchOrdersList($substationlistid, $wardenlistid)
    {
        return DB::table('orders AS o')
            ->leftjoin('dict_wardens AS w', 'o.warden_id', '=', 'w.id')
            ->leftjoin('dict_substations AS s', 'o.substation_id', '=', 's.id')
            ->select('o.id as id', 'o.order_date as order_date', 'w.body as warden', 's.body as substation', 'o.tasks as tasks')
            ->whereIn('o.substation_id', $substationlistid)
            ->whereIn('o.warden_id', $wardenlistid)
            ->orderBy('o.id', 'desc')
            ->paginate(8);
    }

    public function fetchBrigadeMembers($idCollection): array
    {
        return BrigadeMember::
        select('id', 'body', 'group')
            ->whereIn('id', explode(",", $idCollection))
            ->get()
            ->toArray();
    }

    public function getAllPossibleTeamMembersArray(int $branchId): array
    {
        return BrigadeMember::
        select('id', 'body', 'group')
            ->where('branch_id', $branchId)
            ->orderBy('id')
            ->get()
            ->toArray();
    }

    public function fetchBrigadeEngineer($idCollection): array
    {
        return BrigadeEngineer::
        select('id', 'body', 'group', 'specialization')
            ->whereIn('id', explode(",", $idCollection))
            ->get()
            ->toArray();
    }

    public function getAllPossibleTeamEngineerArray(int $branchId): array
    {
        return BrigadeEngineer::
        select('id', 'body', 'group', 'specialization')
            ->where('branch_id', $branchId)
            ->orderBy('id')
            ->get()
            ->toArray();
    }

    public static function readOrderFromDB(Order $order): OrderRecordDTO
    {
        $dto = new OrderRecordDTO();
        $dto->id = $order->id;
        $dto->branchId = $order->branch_id;
        $dto->unitId = $order->unit_id;
        $dto->wardenId = $order->warden_id;
        $dto->adjusterId = $order->adjuster_id;
        $dto->brigadeMembersIds = $order->brigade_m;
        $dto->brigadeEngineerIds = $order->brigade_e;
        $dto->worksSpecsId = $order->works_spec_id;
        $dto->substationId = $order->substation_id;
        $dto->lineId = $order->line_id;
        $dto->objects = $order->objects;
        $dto->tasks = $order->tasks;
        $dto->workBegin = $order->w_begin;
        $dto->workEnd = $order->w_end;
        $dto->separateInstructions = $order->sep_instrs;
        $dto->orderDate = $order->order_date;
        $dto->orderCreator = $order->order_creator;
        $dto->orderLongTo = $order->order_longto;
        $dto->orderLonger = $order->order_longer;
        $dto->underVoltage = $order->under_voltage;
        return $dto;
    }

    public function initOrderRecord(int $branchId, int $worksSpecsId)
    {
        $dto = new OrderRecordDTO();
        $dto->id = 0;
        $dto->branchId = $branchId;
        $dto->unitId = 1;
        $dto->wardenId = 0;
        $dto->adjusterId = 0;
        $dto->brigadeMembersIds = '';
        $dto->brigadeEngineerIds = '';
        $dto->worksSpecsId= $worksSpecsId;
        $dto->substationId = 1;
        $dto->lineId = 0;
        $dto->objects = '';
        $dto->tasks = '';
        $dto->workBegin = '';
        $dto->workEnd = '';
        $dto->separateInstructions = '';
        $dto->orderDate = '';
        $dto->orderCreator = '';
        $dto->orderLongTo = '';
        $dto->orderLonger = '';
        $dto->underVoltage = '';
        return $dto;
    }
}
