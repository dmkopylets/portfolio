<?php

declare(strict_types=1);

namespace App\Services;

use App\RacingData\FilesSourcesPropertiesDto;

class FilesExistChecker
{
    private array $absentsList;
    private bool $exist;

    public function __construct(FilesSourcesPropertiesDto $sources)
    {
        $this->exist = true;
        $fileList = [
            $sources->getStartLog(),
            $sources->getFinishLog(),
            $sources->getAbbreviation(),
        ];
        foreach ($fileList as $value){
            $this->exist(base_path() . '/' . $sources->getFolderName(), $value);
        }
    }

    private function exist(string $filesFolder, string $fileName): void
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
