<?php

declare(strict_types=1);

namespace App\Http\Controllers\Racing;

use App\Http\Controllers\Controller;
use App\RacingData\BuildDataReport;
use App\Services\SourceInitService;

class FlightsRenderController extends ReportController
{
    public function index(SourceInitService $initedSources)
    {
        $report = new BuildDataReport($initedSources->getDriversList(), $initedSources->getFlightsList(), $this::orderingIsDesc());
        $orderedArray = $report->getOrderedArray();
        return view('racingReport.flights.index', ['flights' => $orderedArray]);
    }
}
