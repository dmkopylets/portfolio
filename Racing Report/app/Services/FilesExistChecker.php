<?php

declare(strict_types=1);

namespace App\Services;

use App\RacingData\SourcesFilesPropertyDto;

class FilesExistChecker
{
    private array $absentsList;
    private bool $exist;

    public function __construct(SourcesFilesPropertyDto $sourceSettings)
    {
        $this->exist = true;
        $fileList = [
            $sourceSettings->startLog,
            $sourceSettings->finishLog,
            $sourceSettings->abbreviation,
        ];
        foreach ($fileList as $value){
            $this->checkEveryFile($sourceSettings->basePath . $sourceSettings->folderName, $value);
        }
    }

    private function checkEveryFile(string $filesFolder, string $fileName): void
    {
        if (!file_exists($filesFolder . '/' . $fileName)) {
            $this->exist = false;
            $this->absentsList[] = $fileName;
        }
    }

    public function getAbsentsList(): array
    {
        return $this->absentsList;
    }

    public function isExist(): bool
    {
        return $this->exist;
    }
}
