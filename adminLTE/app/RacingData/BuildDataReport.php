<?php

declare(strict_types=1);

namespace App\RacingData;

use DateTimeImmutable;

class BuildDataReport
{
    protected DriverStorage $drivers;
    protected FlightStorage $flights;
    protected string $logsLocation;
    protected array $orderedArray;
    protected ?string $driverId;
    protected string $reportDriversName;
    protected array $reportDriversData;

    public function __construct()
    {
        $this->logsLocation = __DIR__ . '/../../' . env('RACING_DATAFILES_LOCATION');
        if ($this->filesExist($this->logsLocation)) {
            $this->orderedArray = $this->initData();
            $this->readParameters();
        }
    }

    public function initData(): array
    {
        $this->flights = new FlightStorage();
        $this->readDrivers($this->logsLocation . '/' . env('ABBREVIATION'));
        $this->flights->setDrivers($this->drivers);
        $this->readLog('start', $this->logsLocation . '/' . env('START_LOG'));
        $this->readLog('end', $this->logsLocation . '/' . env('FINISH_LOG'));
        $descending = false;
        if (request()->query('order')==='desc') {
            $descending = true;
        }
        $orderedFlights = new ScoreSorter($this->flights, $descending);
        return $orderedFlights->getResult();
    }

    public function getFlights(): FlightStorage
    {
        return $this->flights;
    }

    public function getOrderedArray(): array
    {
        return $this->orderedArray;
    }

    public function setReportDriversName(string $nameView): void
    {
        $this->reportDriversName = $nameView;
    }

    public function getReportDriversName(): string
    {
        return $this->reportDriversName;
    }

    public function setReportDriversData(array $dataView): void
    {
        $this->reportDriversData = $dataView;
    }

    public function getReportDriversData(): array
    {
        return $this->reportDriversData;
    }

    public function readParameters():void
    {
        $this->driverId = request()->query('driver_id');
        $this->setReportDriversName('racingReport.drivers.index');
        $this->setReportDriversData(['flights'=>$this->orderedArray]);

        if ($this->driverId !== null){
            $this->setReportDriversName('racingReport.drivers.showOne');
            $flightArray = $this->renderOneDriver($this->driverId);
            $this->setReportDriversData($flightArray);
        }
    }

    private function renderOneDriver(string $driverId): array
    {
        $driverInfo = $this->getFlights()->find($driverId);
        return [
            'position'=> $driverInfo->getPossition(),
            'name'=> $driverInfo->getDriverName(),
            'team'=> $driverInfo->getTeam(),
            'start'=> (string) $driverInfo->getStart()->format('i:s.v'),
            'finish'=> (string) $driverInfo->getFinish()->format('i:s.v'),
            'result' => $driverInfo->setDuration($driverInfo->getStart(), $driverInfo->getFinish()),
        ];
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
            if ($typeLog === 'start') {
                $start = new DateTimeImmutable($timeString);
                $this->flights->addFlightStart($index, $start);
            }
            if ($typeLog === 'end') {
                if (trim($timeString) === '') {
                    $this->flights->dropFlight($index);
                } else {
                    $finish = new DateTimeImmutable($timeString);
                    $this->flights->addFlightFinish($index, $finish);
                }
            }
        }
    }

    public function filesExist(string $logsLocation): bool
    {
        $fileName = '';
        $result = true;
        if (!file_exists($logsLocation . '/' . env('START_LOG'))) {
            $fileName = env('START_LOG');
            $result = false;
        }
        if (!file_exists($logsLocation . '/' . env('FINISH_LOG'))) {
            $fileName = env('FINISH_LOG');
            $result = false;
        }
        if (!file_exists($logsLocation . '/' . env('ABBREVIATION'))) {
            $fileName = env('ABBREVIATION');
            $result = false;
        }
        if (!$result) {
            echo('file ' . $fileName . ' does`t exists in folder  "' . $logsLocation . '"');
        }
        return $result;
    }
}
