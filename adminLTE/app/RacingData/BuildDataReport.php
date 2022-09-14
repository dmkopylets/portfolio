<?php

declare(strict_types=1);

namespace App\RacingData;

class BuildDataReport
{
    protected DriverStorage $drivers;
    protected FlightStorage $flights;
    protected bool $descending;
    protected bool $driverListFull;
    protected string $driverId;

    public function initData(): array|bool
    {
        $logsLocation = __DIR__.'/../../Report of Monaco 2018 Racing';
        if ($this->filesExist($logsLocation)) {
            $this->readParameters();
            $this->flights = new FlightStorage();
            $this->readDrivers($logsLocation . '/abbreviations.txt');
            $this->flights->setDrivers($this->drivers);
            $this->readLog('start', $logsLocation . '/start.log');
            $this->readLog('end', $logsLocation . '/end.log');
            $orderedFlights = new ScoreSorter($this->flights, $this->descending);
            return $orderedFlights->getResult();
        }
        return false;
    }

    public function getFlights(): FlightStorage
    {
        return $this->flights;
    }

    public function isDriverListFull(): bool
    {
        return $this->driverListFull;
    }

    public function getDriverId(): string
    {
        return $this->driverId;
    }

    public function readParameters()
    {
        $this->descending = false;
        if (request()->query('order')=='desc'){
            $this->descending = true;
        }
        $driverId = request()->query('driver_id');
        $this->driverListFull = true;
        if ($driverId !== null){
            $this->driverListFull = false;
            $this->driverId = $driverId;
        }
    }

    public function readDrivers(string $dataFile): void
    {
        $txtFile = file_get_contents($dataFile);
        $rows = explode("\n", $txtFile);
        $driversData = new DriverStorage();
        foreach ($rows as $data) {
            $index = substr($data, 0, 3);
            $name = substr($data, 4, 26);
            $team = substr($data, 30, 25);
            $driversData->addDriver(new Driver($index, $name, $team));
        }
        $this->drivers = $driversData;
    }

    public function readLog(string $typeLog, string $dataFile): void
    {
        $txtFile = file_get_contents($dataFile);
        $rows = explode("\n", $txtFile);
        foreach ($rows as $data) {
            $index = substr($data, 0, 3);
            $timeString = substr($data, 3, 10) . ' ' . substr($data, 14, 12);
            if ($typeLog == 'start') {
                $start = new \DateTimeImmutable($timeString);
                $this->flights->addFlightStart($index, $start);
            }
            if ($typeLog == 'end') {
                if (trim($timeString) == '') {
                    $this->flights->dropFlight($index);
                } else {
                    $finish = new \DateTimeImmutable($timeString);
                    $this->flights->addFlightFinish($index, $finish);
                }
            }
        }
    }

    public function filesExist(string $logsLocation): bool
    {
        $fileName = '';
        $result = true;
        if (!file_exists($logsLocation . '/start.log')) {
            $fileName = 'start.log';
            $result = false;
        }
        if (!file_exists($logsLocation . '/end.log')) {
            $fileName = 'end.log';
            $result = false;
        }
        if (!file_exists($logsLocation . '/abbreviations.txt')) {
            $fileName = 'abbreviations.txt';
            $result = false;
        }
        if (!$result) {
            echo('file ' . $fileName . ' does`t exists in folder  "' . $logsLocation . '"');
        }
        return $result;
    }
}
