<?php

declare(strict_types=1);

namespace App\RacingData;

use App\Exceptions\FlightNotFoundException;
use SplObjectStorage;

class FlightStorage
{
    protected SplObjectStorage $flights;

    public function __construct()
    {
        $this->flights = new SplObjectStorage();
    }

    public function getList(): SplObjectStorage
    {
        return $this->flights;
    }

    public function addFlightStart(string $index, \DateTimeImmutable $start): void
    {
        $flight = new OneFlightData();
        $flight->setDriverId($index);
        $flight->setStart($start);
        $this->flights->attach($flight);
    }

    public function addFlightFinish(string $index, \DateTimeImmutable $finish): void
    {
        $flight = $this->find($index);
        $flight->setFinish($finish);
        $flight->setDuration($flight->getStart(), $finish);
    }

    public function find(string $abbreviation): OneFlightData
    {
        $this->flights->rewind();
        while ($this->flights->valid()) {
            $flight = $this->flights->current();
            if (trim($abbreviation) !== '') {
                if ($flight->getDriverId() === $abbreviation) {
                    return $flight;
                }
            }
            $this->flights->next();
        }
        throw new FlightNotFoundException(sprintf('Flight with driver abbreviation "' . $abbreviation . '" not found.', $abbreviation));
    }
}
