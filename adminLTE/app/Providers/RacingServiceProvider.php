<?php

declare(strict_types=1);

namespace App\Providers;

use App\RacingData\FilesSourcesPropertiesDto;
use App\Services\SourceInitService;
use App\Services\OrderingDescReader;
use Illuminate\Support\ServiceProvider;

class RacingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(SourceInitService::class, function ($app)
        {
            $sources = new FilesSourcesPropertiesDto(
                env('RACING_DATAFILES_LOCATION'),
                env('START_LOG'),
                env('FINISH_LOG'),
                env('ABBREVIATION')
            );
            return new SourceInitService($sources);
        });
    }

    public function boot()
    {
        //
    }
}
