<?php

declare(strict_types=1);

namespace App\RacingData;

class SelectOneDriver
{
    private array $driverFlightInfo;

    public function __construct(string $driverId, array $orderedDriversArray)
    {
        $this->driverFlightInfo = $this->renderOneDriver($driverId,  $orderedDriversArray);
    }

    private function renderOneDriver(string $driverId, array $orderedDriversArray): array
    {
        return $orderedDriversArray[$driverId];
    }

    public function getDriverFlightInfo(): array
    {
        return $this->driverFlightInfo;
    }
}
