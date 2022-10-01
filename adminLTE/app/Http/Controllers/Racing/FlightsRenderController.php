<?php

declare(strict_types=1);

namespace App\Http\Controllers\Racing;

use App\Http\Controllers\Controller;
use App\RacingData\BuildDataReport;
use App\Services\SourceInitService;
use App\Services\OrderingDescReader;

class FlightsRenderController extends Controller
{
    public function index(SourceInitService $initedSources)
    {
        $orderingDesc = new OrderingDescReader();
        $report = new BuildDataReport($initedSources->getDriversList(), $initedSources->getFlightsList(), $orderingDesc->getIsDesc());
        $orderedArray = $report->getOrderedArray();
        return view('racingReport.flights.index', ['flights'=>$orderedArray]);
    }
}
