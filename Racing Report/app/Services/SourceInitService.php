<?php

declare(strict_types=1);

namespace App\Services;

use App\RacingData\FileReaderDrivers;
use App\RacingData\FileReaderLogs;
use App\RacingData\SourcesFilesPropertyDto;
use App\RacingData\FlightStorage;
use App\Services\Interfaces\SourceInitContract;

class SourceInitService implements SourceInitContract
{
    private SourcesFilesPropertyDto $sources;
    protected FilesExistChecker $fileChecker;

    public function __construct()
    {
        $settings = new SourcesFilesPropertyDto();
        $this->sources = $settings;
        $this->fileChecker = new FilesExistChecker($settings);
    }

    public function SoucesFilesIsExist(): bool
    {
        return $this->fileChecker->isExist();
    }

    public function getAbsentsFilesList(): array
    {
        return $this->fileChecker->getAbsentsList();
    }

    public function getSourcesFilesPropertyDto(): SourcesFilesPropertyDto
    {
        return $this->sources;
    }
}
