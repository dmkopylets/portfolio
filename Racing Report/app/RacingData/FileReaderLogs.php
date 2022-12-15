<?php

declare(strict_types=1);

namespace App\RacingData;

use App\Http\Controllers\Frontend\FlightsController;
use DateTimeImmutable;

class FileReaderLogs extends FlightsController
{
    private SourcesFilesPropertyDto $sourceSettings;
    private FlightStorage $flightStorage;

    public function read(SourcesFilesPropertyDto $sourceSettings): FlightStorage
    {
        $this->sourceSettings = $sourceSettings;
        $this->flightStorage = new FlightStorage();
        $this->readLog($sourceSettings->startLog);
        $this->readLog($sourceSettings->finishLog);
        return $this->flightStorage;
    }

    private function readLog(string $logFile): void
    {
        $filesLocation = $this->sourceSettings->basePath . $this->sourceSettings->folderName . '/';
        $txtFile = file_get_contents($filesLocation . $logFile);
        $rows = explode("\n", $txtFile);
        foreach ($rows as $row) {
            $row = str_replace('_', ' ', $row);
            $pattern = '/^(?<driverId>^[A-Z]{3})(?<timeString>\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}.\d{3})$/';
            preg_match($pattern, $row, $matches);
            if (isset($matches["driverId"])) {
                $index = $matches["driverId"];
                $timeString = $matches["timeString"];
                $this->putData($logFile, $index, $timeString);
            }
        }
    }

    private function putData(string $logFile, string $index, string $timeString): void
    {
        $time = new DateTimeImmutable($timeString);
        if ($logFile === $this->sourceSettings->startLog) {
            $this->flightStorage->addFlightStart($index, $time);
        }
        if ($logFile === $this->sourceSettings->finishLog) {
            $this->flightStorage->addFlightFinish($index, $time);
        }
    }
}
