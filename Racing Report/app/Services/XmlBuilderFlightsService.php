<?php

declare(strict_types=1);

namespace App\Services;

use SimpleXMLElement;

class XmlBuilderFlightsService
{
    private array $flightsArray;

    public function build(array $flightsArray): string
    {
        $xmlString = new SimpleXMLElement('<flights/>');

        foreach ($flightsArray as $flight) {
            $item = $xmlString->addChild($flight['driverId']);
            $item->addChild('possition', strval($flight['possition']));
            $item->addChild('driver', $flight['driver']);
            $item->addChild('team', $flight['team']);
            $item->addChild('start', $flight['start']);
            $item->addChild('finish', $flight['finish']);
            $item->addChild('duration', $flight['duration']);
            $isOnTop = !empty($flight['top']) ? 'True' : 'False';
            $item->addChild('onTop', $isOnTop);
        }
        return $xmlString->asXML();
    }
}
