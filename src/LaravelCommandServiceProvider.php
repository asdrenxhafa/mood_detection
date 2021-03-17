<?php

namespace asdrenxhafa\mooddetection;

use Illuminate\Support\ServiceProvider;
use asdrenxhafa\mooddetection\Console\Commands\TrainCommand;

class LaravelCommandServiceProvider extends ServiceProvider
{

    protected $commands = [
        TrainCommand::class
    ];

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
    }
}
