<?php

declare(strict_types=1);

namespace App\Services;

class FilesExistChecker
{
    private array $absentsList;
    private bool $exist;

    public function __construct(array $sources)
    {
        $this->exist = true;
        $fileList = [
            $sources['startLog'],
            $sources['finishLog'],
            $sources['abbreviation'],
        ];
        foreach ($fileList as $value){
            $this->exist(__DIR__ . '/../../' . $sources['folderName'], $value);
        }
    }

    private function exist(string $filesFolder, string $fileName): void
    {
        if (!file_exists($filesFolder . '/' . env($fileName))) {
            $this->exist = false;
            $this->absentsList[] = env($fileName);
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
