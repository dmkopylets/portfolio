<?php

declare(strict_types=1);

namespace App\RacingData;

class DriverReportOptionReader
{
    private DriversReport $driversReport;

    public function __construct(array $orderedFlightsArray, ?string $driverId)
    {
        if ($driverId !== null) {
            $oneDriverInfo = new SelectOneDriver($driverId, $orderedFlightsArray);
            $this->driversReport = new DriversReport('racingReport.drivers.showOne', $oneDriverInfo->getDriverFlightInfo());
        } else {
            $this->driversReport = new DriversReport('racingReport.drivers.list', $orderedFlightsArray);
        }
    }

    public function getData(): DriversReport
    {
        return $this->driversReport;
    }
}
