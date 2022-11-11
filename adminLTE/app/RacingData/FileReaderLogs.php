<?php

declare(strict_types=1);

namespace App\RacingData;

use DateTimeImmutable;

class FileReaderLogs
{
    private string $fileLocation;
    private DriverStorage $driversList;
    private FlightStorage $flightStorage;

    public function __construct(string $fileLocation, DriverStorage $driversList)
    {
        $this->fileLocation = $fileLocation;
        $this->driversList = $driversList;
        $this->flightStorage = new FlightStorage();
        $this->readLog(env('START_LOG'));
        $this->readLog(env('FINISH_LOG'));
    }

    public function get()
    {
        return $this->flightStorage;
    }

    private function readLog(string $logFile):void
    {
        $this->flightStorage->setDrivers($this->driversList);
        $txtFile = file_get_contents(base_path() . '/' . $this->fileLocation . '/' .  $logFile);
        $rows = explode("\n", $txtFile);
        foreach ($rows as $data) {
            $index = substr($data, 0, 3);
            $timeString = substr($data, 3, 10) . ' ' . substr($data, 14, 12);
            $this->putData($logFile, $index, $timeString);
        }
    }

    private function putData(string $logFile, string $index, string $timeString): void
    {
        $time = new DateTimeImmutable($timeString);
        if ($logFile === env('START_LOG')) {
            $this->flightStorage->addFlightStart($index, $time);
        }
        if ($logFile === env('FINISH_LOG')) {
            if (trim($timeString) === '') {
                $this->flightStorage->dropFlight($index);
            } else {
                $this->flightStorage->addFlightFinish($index, $time);
            }
        }

    }
}
