<?php

declare(strict_types=1);

namespace App\ReadModel;

use App\Model\Ejournal\Dicts\Substation;
use Illuminate\Database\Eloquent\Collection;


class SubstationFetcher
{
    private $branchId;
    private $substationTypeId;


    public function __construct(int $branchId, int $substationTypeId)
    {
        $this->branchId = $branchId;
        $this->substationTypeId = $substationTypeId;
    }

    public function getSubstationsList(): Collection
    {
        return Substation::
        select('id', 'body', 'type_id')
            ->where('branch_id', $this->branchId)
            ->where('type_id', $this->substationTypeId)
            ->orderBy('type_id', 'asc')
            ->orderBy('body', 'asc')
            ->get();
    }
}
