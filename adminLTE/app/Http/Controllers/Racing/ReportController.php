<?php

declare(strict_types=1);

namespace App\Http\Controllers\Racing;

use App\RacingData\BuildDataReport;
use App\Services\SourceInitService;
use Illuminate\Routing\Controller as BaseController;

class ReportController extends BaseController
{
    protected BuildDataReport $report;

    protected function getData(SourceInitService $initedSources)
    {
        return new BuildDataReport($initedSources->getDriversList(), $initedSources->getFlightsList(), $this::orderingIsDesc());
    }

    protected function orderingIsDesc(): bool
    {
        $descending = false;
        if (request()->query('order') === 'desc') {
            $descending = true;
        }
        return $descending;
    }

    protected function getDriverId(): string|null
    {
        return request()->query('driver_id');
    }
}
