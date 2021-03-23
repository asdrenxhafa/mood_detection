<?php

namespace asdrenxhafa\mooddetection\Providers;

use Illuminate\Support\ServiceProvider;
use asdrenxhafa\mooddetection\Console\Commands\TrainCommand;

class MoodDetectionCommandServiceProvider extends ServiceProvider
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
