<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

interface SourceInitContract
{
    public function isExist(): bool;
    public function getAbsentsList(): array;
}
