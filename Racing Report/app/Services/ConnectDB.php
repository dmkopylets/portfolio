<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;

class ConnectDB
{
    public function bootEloquent(SourceInitService $setup): void
    {
        $capsule = new Capsule;
        $capsule->addConnection([
            'driver' => $setup->getSourcesFilesPropertyDto()->dbDriver,
            'host' => $setup->getSourcesFilesPropertyDto()->dbHost,
            'database' => $setup->getSourcesFilesPropertyDto()->dbDatabase,
            'username' => $setup->getSourcesFilesPropertyDto()->dbUserName,
            'password' => $setup->getSourcesFilesPropertyDto()->dbPassword,
        ]);
        $capsule->setEventDispatcher(new Dispatcher(new Container));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}
