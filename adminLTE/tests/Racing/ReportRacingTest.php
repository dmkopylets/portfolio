<?php

namespace Tests\Racing;

use App\RacingData\Flight;
use Tests\TestCase;

class ReportRacingTest extends TestCase
{
    public function testTheApplicationRreturnsASuccessfulResponse1()
    {
        $response = $this->get('/report/');
        static::assertEquals(200,$response->status());
    }

    public function testTheApplicationRreturnsASuccessfulResponse2()
    {
        $response = $this->get('/report/drivers/?driver_id=SVM');
        static::assertEquals(200,$response->status());
    }

    public function testTheApplicationRreturnsASuccessfulResponse3()
    {
        $response = $this->get('/report/?order=desc');
        $response->assertOk();
    }

    public function testRightPath()
    {
        $dataReport = new \App\RacingData\BuildDataReport();
        static::assertTrue($dataReport->filesExist(__DIR__.'/../../Report of Monaco 2018 Racing'));
    }

    /**
     * @dataProvider flightDataProvider
     */
    public function testClassFight(string $expectedString, array $flightData)
    {
        $driverId = $flightData['driverId'];
        $driverName = $flightData['driverName'];
        $team = $flightData['team'];
        $start = new \DateTimeImmutable($flightData['start']);
        $finish = new \DateTimeImmutable($flightData['finish']);
        $flight = new Flight($driverId, $driverName, $team, $start);
        $actualString = $flight->getDuration($start, $finish);
        $this->assertEquals(expected: $expectedString, actual:  $actualString);
    }
    public function flightDataProvider() : array
    {
        return [
            'one'=>['01:11.096',['driverId'=>'TT1', 'driverName'=>'Testing Name1', 'team'=>'Testing', 'start'=>'2018-05-24 12:02:58.917000', 'finish'=>'2018-05-24 12:04:10.013000']],
            'two'=>['01:11.498',['driverId'=>'TT2', 'driverName'=>'Testing Name2', 'team'=>'Testing', 'start'=>'2018-05-24 12:02:58.917000', 'finish'=>'2018-05-24 12:04:10.415000']],
            'three'=>['01:11.543',['driverId'=>'TT2', 'driverName'=>'Testing Name2', 'team'=>'Testing', 'start'=>'2018-05-24 12:02:58.917000', 'finish'=>'2018-05-24 12:04:10.460000']],
        ];
    }
}
