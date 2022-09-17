<?php

declare(strict_types=1);

namespace App\Http\Controllers\Racing;

use App\Http\Controllers\Controller;
use App\RacingData\BuildDataReport;

class FlightsRenderController extends Controller
{
    public function index()
    {
        $report = new BuildDataReport();
        $orderedArray = $report->getOrderedArray();
            return view('racingReport.flights.index', ['flights'=>$orderedArray]);
    }
}
