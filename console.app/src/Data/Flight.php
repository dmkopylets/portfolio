<?php

declare(strict_types=1);

namespace App\Data;

class Flight
{
    private string $driverId;
    private string $driverName;
    private string $team;
    private \DateTimeImmutable $start;
    private \DateTimeImmutable $finish;
    private string $duration;

    public function __construct(string $driverId, string $driverName, string $team, \DateTimeImmutable $start)
    {
        $this->driverId = $driverId;
        $this->driverName = $driverName;
        $this->team = $team;
        $this->start = $start;
    }

    public function getDriverId(): string
    {
        return $this->driverId;
    }
    public function getDriverName(): string
    {
        return trim($this->driverName);
    }
    public function getTeam(): string
    {
        return $this->team;
    }

    public function getStart(): \DateTimeImmutable
    {
        return $this->start;
    }
    public function setStart(\DateTimeImmutable $start): Flight
    {
        $this->start = $start;
        return $this;
    }
    public function getFinish(): \DateTimeImmutable
    {
        return $this->finish;
    }

    public function setFinish(\DateTimeImmutable $finish): Flight
    {
        $this->finish = $finish;
        return $this;
    }

    public function getDuration(\DateTimeImmutable $start, \DateTimeImmutable $finish): string
    {
        $diff1 = $start->diff($finish)->format('%I');
        $diff2 = $start->diff($finish)->format('%S');
        $diff3 = str_pad(strval(intval($start->diff($finish)->format('%f')) / 1000), 3, '0', STR_PAD_LEFT);
        $diff = (string)$diff1 . ':' . (string)$diff2 . '.' . $diff3;
        $this->duration = $diff;
        return $diff;
    }

    public function getDurationInt(\DateTimeImmutable $start, \DateTimeImmutable $finish): int
    {
        $diff1 = intval($start->diff($finish)->format('%I%S')) * 1000000;
        $diff2 = intval($start->diff($finish)->format('%f'));
        $diff3 = intval($diff1 + $diff2) / 1000 ;
        return $diff3;
    }
}
