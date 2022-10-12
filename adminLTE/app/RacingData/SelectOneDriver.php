<?php

declare(strict_types=1);

namespace App\RacingData;

class SelectOneDriver
{
    private array $driverFightInfo;

    public function __construct(string $driverId, array $orderedDriversArray)
    {
        $this->driverFightInfo = $this->renderOneDriver($driverId,  $orderedDriversArray);
    }

    private function renderOneDriver(string $driverId, array $orderedDriversArray): array
    {
        return $orderedDriversArray[$driverId];
    }

    public function getDriverFightInfo(): array
    {
        return $this->driverFightInfo;
    }
}
