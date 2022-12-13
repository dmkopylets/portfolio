#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once  __DIR__ . '/vendor/autoload.php';

use App\Services\TxtDataImportCommand;
use Symfony\Component\Console\Application as SymfonyApplication;

$app = new SymfonyApplication('Racing Report data importer', 'v1.0.0');
$app->add(new TxtDataImportCommand());
try {
    $app->run();
} catch (Exception $e) {
}
