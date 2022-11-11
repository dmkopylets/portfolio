<?php

declare(strict_types=1);

namespace App\Http\Controllers\Racing;

use App\RacingData\DriverReportOptionReader;
use App\Services\SourceInitService;

class DriversRenderController extends ReportController
{
    public function index(SourceInitService $initedSources)
    {
        $driverId = $this->getDriverId();
        $orderedFlightsArray = $this->getData($initedSources)->getOrderedArray();
        $renderedReport = new DriverReportOptionReader($orderedFlightsArray, $driverId);
        return view($renderedReport->getData()->getReportName(), ['reportDriversData' => $renderedReport->getData()->getReportData()]);
    }
}
