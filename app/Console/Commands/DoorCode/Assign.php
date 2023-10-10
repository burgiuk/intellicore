<?php

namespace App\Console\Commands\DoorCode;

use App\Http\Controllers\DoorCodesController;
use Illuminate\Console\Command;

class Assign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'doorcode:assign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DoorCodesController::assignDoorCode('test','651234');
    }
}
