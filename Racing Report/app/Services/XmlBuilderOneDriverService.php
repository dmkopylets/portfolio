<?php

declare(strict_types=1);

namespace App\Services;

use SimpleXMLElement;

class XmlBuilderOneDriverService
{
    public function build(array $driverInfoArray): string
    {
        $xmlString = new SimpleXMLElement('<driver/>');
        $xmlString->addChild('key', $driverInfoArray['driverId']);
        $xmlString->addChild('possition', strval($driverInfoArray['possition']));
        $xmlString->addChild('driver', $driverInfoArray['driver']);
        $xmlString->addChild('team', $driverInfoArray['team']);
        $xmlString->addChild('start', $driverInfoArray['start']);
        $xmlString->addChild('finish', $driverInfoArray['finish']);
        $xmlString->addChild('duration', $driverInfoArray['duration']);
        return $xmlString->asXML();
    }
}
