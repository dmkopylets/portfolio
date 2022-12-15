<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Controllers\Frontend\FlightsController;
use App\Models\RacingData\Flight;
use App\RacingData\OneFlightData;
use App\RacingData\FlightStorage;

class ExportToDbFlights extends FlightsController
{
    public function store(FlightStorage $flights): void
    {
        Flight::truncate();
        foreach ($flights->getList() as $flight) {
            $this->storeOne($flight);
        }
    }

    public function storeOne(OneFlightData $flightData): void
    {
        if ($flightData->getFinish()) {
            $record = new Flight();
            $record->driverId = $flightData->getDriverId();
            $record->start = $flightData->getStart()->format('Y-m-d H:i:s.v');
            $record->finish = $flightData->getFinish()->format('Y-m-d H:i:s.v');
            $record->duration = strval($flightData->getDuration());
            $record->save();
        }
    }
}
