<?php

declare(strict_types=1);

namespace App\RacingData;

class BuildDataReport
{
    protected DriverStorage $driversList;
    protected FlightStorage $flights;
    protected array $orderedArray;
    private bool $orderingIsDesc;

    public function __construct(DriverStorage $driversList, FlightStorage $flights, bool $orderingIsDesc)
    {
        $this->driversList = $driversList;
        $this->flights = $flights;
        $this->orderingIsDesc = $orderingIsDesc ;
        $this->orderedArray = $this->orderData();
    }

    public function orderData(): array
    {
        $orderedFlights = new ScoreSorter($this->flights, $this->orderingIsDesc);
        return $orderedFlights->getScore();
    }

    public function getOrderedArray(): array
    {
        return $this->orderedArray;
    }
}
