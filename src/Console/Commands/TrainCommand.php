<?php

namespace asdrenxhafa\mooddetection\Console\Commands;

use Illuminate\Console\Command;

class TrainCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'train:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trains the ML model';

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
       shell_exec('php train.php');
    }
}