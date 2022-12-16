<?php

declare(strict_types=1);

namespace App\RacingData;

class FileReaderDrivers
{
    private DriverStorage $driverStorage;

    public function read(SourcesFilesPropertyDto $sourceSettings): DriverStorage
    {
        $this->driverStorage = new DriverStorage();
        $txtFile = file_get_contents($sourceSettings->basePath . $sourceSettings->folderName . '/' . $sourceSettings->abbreviation);
        $rows = explode("\n", $txtFile);
        foreach ($rows as $row) {
            $pattern = '/^(?<driverId>[A-Z]{3}) (?<name>[a-zA-Z .]{26})(?<team>[a-zA-Z -]{25})$/';
            preg_match($pattern, $row, $matches);
            $record = new OneDriverData;
            $record->setDriverId($matches['driverId']);
            $record->setName($matches['name']);
            $record->setTeam($matches['team']);
            $this->driverStorage->add($record);
        }
        return $this->driverStorage;
    }
}
