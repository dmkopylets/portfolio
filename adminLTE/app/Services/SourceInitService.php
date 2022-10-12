<?php

declare(strict_types=1);

namespace App\Services;

use App\RacingData\DriverStorage;
use App\RacingData\FileReaderDrivers;
use App\RacingData\FileReaderLogs;
use App\RacingData\FlightStorage;
use App\Services\Interfaces\SourceInitContract;

class SourceInitService implements SourceInitContract
{
    private array $sources;
    protected FilesExistChecker $fileChecker;

    public function __construct(array $sources)
    {
        $this->sources = $sources;
        $this->fileChecker = new FilesExistChecker($sources);
    }

    public function isExist(): bool
    {
        return $this->fileChecker->isExist();
    }

    public function getAbsentsList(): array
    {
        return $this->fileChecker->getAbsentsList();
    }

    public function getDriversList(): DriverStorage
    {
        $driversReader = new FileReaderDrivers($this->sources);
        $driversList = $driversReader->get();
        return  $driversList;
    }

    public function getFlightsList(): FlightStorage
    {
        $driversReader = new FileReaderDrivers($this->sources);
        $logsReader = new FileReaderLogs($this->sources['folderName'], $driversReader->get());
        return $logsReader->get();
    }
}
