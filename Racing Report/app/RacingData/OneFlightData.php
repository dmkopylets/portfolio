<?php

declare(strict_types=1);

namespace App\RacingData;

class OneFlightData
{
    private string $driverId;
    private \DateTimeImmutable $start;
    private \DateTimeImmutable $finish;
    private string $duration;

    public function getDriverId(): string
    {
        return $this->driverId;
    }

    public function getStart(): \DateTimeImmutable
    {
        return $this->start;
    }

    public function getFinish(): \DateTimeImmutable
    {
        return $this->finish;
    }

    public function isExistFinish(): bool
    {
        return isset($this->finish);
    }

    public function getDuration(): string
    {
        return $this->duration;
    }

    public function setDriverId(string $driverId): void
    {
        $this->driverId = $driverId;
    }

    public function setStart(\DateTimeImmutable $start): void
    {
        $this->start = $start;
    }

    public function setFinish(\DateTimeImmutable $finish): void
    {
        $this->finish = $finish;
    }

    public function setDuration(\DateTimeImmutable $start, \DateTimeImmutable $finish): void
    {
        $diff1 = $start->diff($finish)->format('%I:%S');
        $diff2 = str_pad(strval(intval($start->diff($finish)->format('%f')) / 1000), 3, '0', STR_PAD_LEFT);
        $diff3 = (string)$diff1 . '.' . $diff2;
        $this->duration = $diff3;
    }
}
