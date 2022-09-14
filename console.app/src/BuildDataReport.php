<?php

declare(strict_types=1);

namespace App;

use App\Data\Driver;
use App\Data\DriverStorage;
use App\Data\FlightStorage;

class BuildDataReport
{
    protected DriverStorage $drivers;
    protected FlightStorage $flights;

    public function buildReport(string $logsLocation): FlightStorage
    {
        $this->flights = new FlightStorage();
        $this->readDrivers($logsLocation . '/abbreviations.txt');
        $this->flights->setDrivers($this->drivers);
        $this->readLog('start', $logsLocation . '/start.log');
        $this->readLog('end', $logsLocation . '/end.log');
        return $this->flights;
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
            $dataString = substr($data, 3, 10);
            $timeString = substr($data, 14, 12);
            if ($typeLog == 'start') {
                $start = new \DateTimeImmutable($dataString . ' ' . $timeString);
                $this->flights->addFlightStart($index, $start);
            }
            if ($typeLog == 'end') {
                if (trim($dataString) =='') {
                    $this->flights->dropFlight($index);
                } else {
                    $finish = new \DateTimeImmutable($dataString . ' ' . $timeString);
                    $this->flights->addFlightFinish($index, $finish);
                }
            }
        }
    }
}
