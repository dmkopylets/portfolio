<?php

declare(strict_types=1);

namespace App\Http\Controllers\Racing;

use App\Http\Controllers\Controller;
use App\RacingData\BuildDataReport;
use App\RacingData\DriverReportOptionReader;
use App\Services\OrderingDescReader;
use App\Services\SourceInitService;

class DriversRenderController extends Controller
{
    public function index(SourceInitService $initedSources)
    {
        $orderingDesc = new OrderingDescReader();
        $report = new BuildDataReport($initedSources->getDriversList(), $initedSources->getFlightsList(), $orderingDesc->getIsDesc());
        $orderedFlightsArray = $report->getOrderedArray();
        $driversReport = new DriverReportOptionReader($orderedFlightsArray);
        $reportDriversData = $driversReport->getData();
        return view($reportDriversData['reportName'], ['reportDriversData'=>$reportDriversData['reportData']]);
    }
}
