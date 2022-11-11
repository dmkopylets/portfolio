<?php

declare(strict_types=1);

namespace App\RacingData;

class DriversReport
{
    private string $reportName;
    private array $reportData;

    public function __construct(string $reportName, array $reportData)
    {
        $this->reportName = $reportName;
        $this->reportData = $reportData;
    }

    public function getReportName(): string
    {
        return $this->reportName;
    }

    public function getReportData(): array
    {
        return $this->reportData;
    }
}
