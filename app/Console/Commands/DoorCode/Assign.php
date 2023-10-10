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
    protected $description = 'Assign a specific door code to a person';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // TODO
        // Ask for code


        // Ask for name

        $assigned = DoorCodesController::assignDoorCode($name,$code);
        if(!$assigned){
            // Failure message here
        }

        // Success message here
    }
}
