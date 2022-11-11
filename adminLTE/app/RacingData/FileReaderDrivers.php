<?php

declare(strict_types=1);

namespace App\RacingData;

class FileReaderDrivers
{
    private FilesSourcesPropertiesDto $sources;

    public function __construct(FilesSourcesPropertiesDto $sources)
    {
        $this->sources = $sources;
    }

    public function get(): DriverStorage
    {
        $txtFile = file_get_contents(base_path() . '/' . $this->sources->getFolderName() . '/' . $this->sources->getAbbreviation());
        $rows = explode("\n", $txtFile);
        $driversData = new DriverStorage();
        foreach ($rows as $data) {
            $index = substr($data, 0, 3);
            $name = substr($data, 4, 26);
            $team = substr($data, 30, 25);
            $driversData->addDriver(new Driver($index, $name, $team));
        }
        return $driversData;
    }
}
