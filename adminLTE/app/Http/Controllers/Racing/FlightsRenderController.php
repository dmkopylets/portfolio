<?php

namespace App\Http\Controllers\Racing;

use App\Http\Controllers\Controller;
use App\RacingData\BuildDataReport;

class FlightsRenderController extends Controller
{
    public function index()
    {
        $reportData = new BuildDataReport();
        $orderedArray = $reportData->initData();
            return view('racingReport.flights.index', ['flights'=>$orderedArray]);
    }
}
