<?php

declare(strict_types=1);

namespace App\RacingData;

use App\Models\RacingData\Flight;

class ScoreCalculator
{
    public function run()
    {
        $records = Flight::select(
            'flights.driverId',
            'drivers.name AS driver',
            'drivers.team',
            'flights.start',
            'flights.finish',
            'flights.duration'
        )
            ->leftJoin('drivers', 'drivers.id', '=', 'flights.driverId')
            ->orderBy('duration')
            ->get()
            ->all();
        $i = 1;
        foreach ($records as $record) {
            Flight::where('driverId', $record->driverId)->update(['possition' => $i++]);
        }
        Flight::where('possition', '<', 16)->update(['top' => true]);
        Flight::where('possition', '>', 15)->update(['top' => false]);
    }
}
