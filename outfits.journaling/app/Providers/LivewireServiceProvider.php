<?php
namespace App\Providers;
use App\Http\Controllers\Ejournal\Edit\DirectionTask;
use App\Http\Controllers\Ejournal\Edit\EditPart2Preparation;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
class LivewireServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Livewire::component('direction-task', DirectionTask::class);
        Livewire::component('edit-part2-preparation', EditPart2Preparation::class);
    }
}
