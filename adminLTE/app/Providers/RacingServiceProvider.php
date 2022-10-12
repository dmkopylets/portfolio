<?php

namespace App\Providers;

use App\Services\SourceInitService;
use App\Services\OrderingDescReader;
use Illuminate\Support\ServiceProvider;

class RacingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(SourceInitService::class, function ($app)
        {
            $sources = [
                'folderName' => env('RACING_DATAFILES_LOCATION'),
                'startLog' => env('START_LOG'),
                'finishLog' => env('FINISH_LOG'),
                'abbreviation' => env('ABBREVIATION'),
            ];
            return new SourceInitService($sources);
        });

        $this->app->bind(OrderingDescReader::class, function ($app)
        {
            $order = new OrderingDescReader();
            return $order->getIsDesc();
        });
    }

    public function boot()
    {
        //
    }
}
