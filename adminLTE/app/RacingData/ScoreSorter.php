<?php

namespace App\RacingData;
use App\RacingData\FlightStorage as FlightStorage;

class ScoreSorter

{
    private array $shortArray;
    private FlightStorage $flights;

    public function __construct(FlightStorage $data, bool $descending)
    {
        $this->flights = $data;
        $flightsData = $data->getList();
        $orderedArray = [];
        $flightsData->rewind();
        while ($flightsData->valid()) {
            $flight = $flightsData->current();
            $duration = $this->getDurationInt($flight->getStart(), $flight->getFinish());
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
        $this->shortArray = $orderedArray;
    }

    private function getDurationInt(\DateTimeImmutable $start, \DateTimeImmutable $finish): int
    {
        $diff1 = intval($start->diff($finish)->format('%I%S')) * 1000000;
        $diff2 = intval($start->diff($finish)->format('%f'));
        $diff3 = intval($diff1 + $diff2) / 1000 ;
        return $diff3;
    }

    public function getScore(): array
    {
        $racingArray = [];
        foreach ($this->shortArray as $key => $racingData) {
            $flight = $this->flights->find($racingData['driver']);
            $flight->setPossition($key + 1);
            $racingArray[$flight->getDriverId()] = array(
                'key'=> $flight->getDriverId(),
                'place' => $key + 1,
                'driver' => $flight->getDriverName(),
                'team' => $flight->getTeam(),
                'start' => $flight->getStart()->format('i.s:v'),
                'finish' => $flight->getFinish()->format('i.s:v'),
                'duration' => $flight->setDuration($flight->getStart(), $flight->getFinish()),
                'lined' => $racingData['lined']
            );
        }
        return $racingArray;
    }
}
