<?php

declare(strict_types=1);

namespace App\RacingData;

class OneDriverData
{
    private string $driverId;
    private string $name;
    private string $team;
    private string $start;
    private string $finish;
    private string $duration;
    private string $possition;
    private bool $top;

    public function getDriverId(): string
    {
        return $this->driverId;
    }

    public function setDriverId(string $driverId): void
    {
        $this->driverId = $driverId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getTeam(): string
    {
        return $this->team;
    }

    public function setTeam(string $team): void
    {
        $this->team = $team;
    }

    public function getStart(): string
    {
        return $this->start;
    }

    public function setStart(string $start): void
    {
        $this->start = $start;
    }

    public function setFinish(string $finish): void
    {
        $this->finish = $finish;
    }

    public function getPossition(): string
    {
        return $this->possition;
    }

    public function getTop(): bool
    {
        return $this->top;
    }
}
