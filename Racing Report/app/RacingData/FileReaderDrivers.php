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
        foreach ($rows as $data) {
            $record = new OneDriverData;
            $record->setDriverId(substr($data, 0, 3));
            $record->setName(substr($data, 4, 26));
            $record->setTeam(substr($data, 30, 25));
            $this->driverStorage->add($record);
        }
        return $this->driverStorage;
    }
}
