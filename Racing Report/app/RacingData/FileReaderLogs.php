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
        foreach ($rows as $data) {
            $index = substr($data, 0, 3);
            $timeString = substr($data, 3, 10) . ' ' . substr($data, 14, 12);
            $this->putData($logFile, $index, $timeString);
        }
    }

    private function putData(string $logFile, string $index, string $timeString): void
    {
        $time = new DateTimeImmutable($timeString);
        if ($logFile === $this->sourceSettings->startLog) {
            $this->flightStorage->addFlightStart($index, $time);
        }
        if ($logFile === $this->sourceSettings->finishLog) {
            if (trim($timeString) === '') {
                $this->flightStorage->dropFlight($index);
            } else {
                $this->flightStorage->addFlightFinish($index, $time);
            }
        }

    }
}
