<?php

declare(strict_types=1);

namespace App\RacingData;

use SplObjectStorage;

class DriverStorage
{
    protected SplObjectStorage $drivers;
    private OneDriverData $driver;

    public function __construct()
    {
        $this->drivers = new SplObjectStorage();
    }
    public function getList(): SplObjectStorage
    {
        return $this->drivers;
    }
    public function add(OneDriverData $record): void
    {
        $this->drivers->attach($record);
    }
}
