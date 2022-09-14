#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use App\RacingCommand;
use Symfony\Component\Console\Application;

$app = new Application('Racing Report', 'v1.0.0');
$app->add(new RacingCommand());
try {
    $app->run();
} catch (Exception $e) {
}
