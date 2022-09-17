<?php

declare(strict_types=1);

namespace App\Http\Controllers\Racing;

use App\Http\Controllers\Controller;
use App\RacingData\BuildDataReport;

class DriversRenderController extends Controller
{
    public function index()
    {
        $report = new BuildDataReport();
        return view($report->getReportDriversName(), $report->getReportDriversData());
    }
}
