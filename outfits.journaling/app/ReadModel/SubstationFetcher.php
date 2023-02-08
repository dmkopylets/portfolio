<?php

declare(strict_types=1);

namespace App\ReadModel;

use App\Model\Ejournal\Dicts\Substation;

class SubstationFetcher
{
    private $branchId;
    private $substationTypeId;


    public function __construct(int $branchId, int $substationTypeId)
    {
        $this->branchId = $branchId;
        $this->substationTypeId = $substationTypeId;
    }

    public function getSubstationsList()
    {
        return Substation::
        select('id', 'body')
            ->where('branch_id', $this->branchId)
            ->where('type_id', $this->substationTypeId)
            ->orderBy('type_id', 'asc')
            ->orderBy('body', 'asc')
            ->get();
    }
}
