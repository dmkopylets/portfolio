<?php

declare(strict_types=1);

namespace App\RacingData;

class DriverReportOptionReader
{
    private array $orderedDriversArray;

    public function __construct(array $orderedDriversArray)
    {
        $this->orderedDriversArray = $orderedDriversArray;
    }
    public function getData(): array
    {
        $driverId = request()->query('driver_id');
        if ( $driverId !== null ) {
            $oneDriverInfo = new SelectOneDriver($driverId, $this->orderedDriversArray);
            $reportName = 'racingReport.drivers.showOne';
            $reportData = $oneDriverInfo->getDriverFightInfo();
        } else {
            $reportName = 'racingReport.drivers.index';
            $reportData = $this->orderedDriversArray;
        }
        return ['reportName'=>$reportName, 'reportData'=>$reportData];
    }
}
