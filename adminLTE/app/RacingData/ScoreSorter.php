<?php

namespace App\RacingData;
use App\RacingData\FlightStorage;

class ScoreSorter
    //extends \App\RacingData\FlightStorage
{
    private array $result;
    private FlightStorage $flights;

    public function getResult(): array
    {
        $racingArray = [];
        foreach ($this->result as $key => $racingData) {
            $flight = $this->flights->find($racingData['driver']);
            $flight->setPossition($key + 1);
            $racingArray[] = array(
                'key'=> $flight->getDriverId(),
                'place' => $key + 1,
                'driver' => $flight->getDriverName(),
                'team' => $flight->getTeam(),
                'duration' => $flight->getDuration($flight->getStart(), $flight->getFinish()),
                'lined' => $racingData['lined']
            );
        }
        return $racingArray;
    }

    public function __construct(FlightStorage $data, bool $descending)
    {
        $this->flights = $data;
        $flightsData = $data->getList();
        $orderedArray = [];
        $flightsData->rewind();
        while ($flightsData->valid()) {
            $flight = $flightsData->current();
            $duration = $flight->getDurationInt($flight->getStart(), $flight->getFinish());
            $orderedArray[] = ['driver' => $flight->getDriverId(), 'score' => $duration, 'lined' => false];
            $flightsData->next();
        }
        if ($descending) {
            $lineNumber = count($orderedArray) - 16;
            usort($orderedArray, function ($a, $b) {
                return ($b['score'] - $a['score']);
            });
        } else {
            $lineNumber = 14;
            usort($orderedArray, function ($a, $b) {
                return ($a['score'] - $b['score']);
            });
        }
        $orderedArray[$lineNumber]['lined'] = true;
        $this->result = $orderedArray;
    }
}
