<?php

declare(strict_types=1);

namespace App\RacingData;

use App\Exceptions\DriverNotFoundException;
use SplObjectStorage;

class DriverStorage
{
    protected SplObjectStorage $drivers;

    public function __construct()
    {
        $this->drivers = new SplObjectStorage();
    }

    public function addDriver(Driver $driver): void
    {
        $this->drivers->attach($driver);
    }

    public function find(string $abbreviation): Driver
    {
        $this->drivers->rewind();
        while ($this->drivers->valid()) {
            $driver = $this->drivers->current();
            if (($driver->getIndex()) === $abbreviation) {
                return $driver;
            }
            $this->drivers->next();
        }
        throw new DriverNotFoundException(sprintf('Driver with abbreviation %d not found.', $abbreviation));
    }
}
