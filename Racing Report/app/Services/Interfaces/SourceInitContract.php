<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

interface SourceInitContract
{
    public function SoucesFilesIsExist(): bool;
    public function getAbsentsFilesList(): array;
}
