<?php

declare(strict_types=1);

namespace App\Services;

use SimpleXMLElement;

class XmlBuilderDriversService
{
    public function build(array $driversArray): string
    {
        $xmlString = new SimpleXMLElement('<drivers/>');
        foreach ($driversArray as $driverData) {
            $item = $xmlString->addChild($driverData['id']);
            $item->addChild('name', $driverData['name']);
            $item->addChild('team', $driverData['team']);
        }
        return $xmlString->asXML();
    }
}
