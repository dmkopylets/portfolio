<?php

declare(strict_types=1);

namespace App\Model\Ejournal;

class OrderRecordDTO
{
    public int $id;
    public int $branchId;
    public int $unitId;
    public int $wardenId;
    public int $adjusterId;
    public string $brigadeMembersIds;
    public string $brigadeEngineerIds;
    public int $worksSpecsId;
    public int $substationId;
    public int $lineId;
    public string $objects;
    public string $tasks;
    public string $workBegin;
    public string $workEnd;
    public string $separateInstructions;
    public string $orderDate;
    public string $orderCreator;
    public string $orderLongTo;
    public string $orderLonger;
    public string $underVoltage;
}
