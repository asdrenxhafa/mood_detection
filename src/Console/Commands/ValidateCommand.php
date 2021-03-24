<?php

namespace asdrenxhafa\mooddetection\Console\Commands;

use asdrenxhafa\mooddetection\MoodDetection;
use Illuminate\Console\Command;

class ValidateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'validate:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validates the ML model';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
       $trainer = new MoodDetection();

       $trainer->validate();
    }
}
