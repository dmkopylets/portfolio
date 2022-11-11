<?php

declare(strict_types=1);

namespace App\RacingData;

class FilesSourcesPropertiesDto
{
    private string $folderName;
    private string $startLog;
    private string $finishLog;
    private string $abbreviation;

    public function __construct(string $folderName, string $startLog, string $finishLog, string $abbreviation)
    {
        $this->folderName=$folderName;
        $this->startLog=$startLog;
        $this->finishLog=$finishLog;
        $this->abbreviation=$abbreviation;
    }

    public function getFolderName(): string
    {
        return $this->folderName;
    }

    public function getStartLog(): string
    {
        return $this->startLog;
    }

    public function getFinishLog(): string
    {
        return $this->finishLog;
    }

    public function getAbbreviation(): string
    {
        return $this->abbreviation;
    }
}
