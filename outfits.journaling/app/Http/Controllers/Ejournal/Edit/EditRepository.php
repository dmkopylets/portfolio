<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ejournal\Edit;

use App\Model\Ejournal\Dicts\Line;
use App\Model\Ejournal\Dicts\Substation;

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
            ->get()->toArray();
    }

    public function getLinesList(int $branchId, int $substationId): array
    {
        return Line::
        select('line_id')
            ->where('branch_id', $branchId)
            ->where('substation_id', $substationId)
            ->orderBy('line_id', 'asc')
            ->get()->toArray();
    }
}
