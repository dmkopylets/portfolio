<?php

namespace App\Http\Controllers\Racing;

use App\Http\Controllers\Controller;
use App\RacingData\BuildDataReport;
use Nyholm\Psr7\Request;

class DriversRenderController extends Controller
{
    public function index()
    {
        $reportData = new BuildDataReport();
        $orderedArray = $reportData->initData();
        if ($reportData->isDriverListFull() == 1){
            return view('racingReport.drivers.index', ['flights'=>$orderedArray]);
        } else {
            $driverId = $reportData->getDriverId();
            $driverInfo = $reportData->getFlights()->find($driverId);
            //$driverInfo =  $orderedFlights->find($driverId);
            return view('racingReport.drivers.showOne', [
                'position'=> $driverInfo->getPossition(),
                'name'=> $driverInfo->getDriverName(),
                'team'=> $driverInfo->getTeam(),
                'start'=> (string) $driverInfo->getStart()->format('i:s.v'),
                'finish'=> (string) $driverInfo->getFinish()->format('i:s.v'),
                'result' => $driverInfo->getDuration($driverInfo->getStart(), $driverInfo->getFinish()),
            ]);
        }
    }
}
