<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\RacingData\Driver;
use App\RacingData\OneDriverData;
use App\RacingData\DriverStorage;

class ExportToDbDrivers
{
    public function store(DriverStorage $drivers): void
    {
        Driver::truncate();
        foreach ($drivers->getList() as $driver) {
            $this->storeOne($driver);
        }
    }

    public function storeOne(OneDriverData $driverData): void
    {
        $record = new Driver;
        $record->id = $driverData->getDriverId();
        $record->name = $driverData->getName();
        $record->team = $driverData->getTeam();
        $record->save();
    }
}
