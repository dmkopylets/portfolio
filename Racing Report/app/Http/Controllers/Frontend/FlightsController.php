<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\ReportController;
use App\Models\RacingData\Flight;

class FlightsController extends ReportController
{
    public function index()
    {
        $reportData = new ReportController;
        $ordering = $reportData->orderingIsDesc();
        $records = Flight::select(
            'flights.driverId',
            'drivers.name AS driver',
            'drivers.team',
            'flights.start',
            'flights.finish',
            'flights.duration',
            'flights.possition',
            'flights.top'
        )
            ->leftJoin('drivers', 'drivers.id', '=', 'flights.driverId')
            ->orderBy('duration',$ordering)
            ->get();
        return view('racingReport.flights.index', ['flights' => $records]);
    }
}
