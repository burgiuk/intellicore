<?php

namespace App\Console\Commands\DoorCode;

use App\Http\Controllers\DoorCodesController;
use Illuminate\Console\Command;

class Unallocated extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'doorcode:unallocated';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculates the number of available codes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $doorcodes = new DoorCodesController();
        $unallocated = $doorcodes->calculateUnallocated();

        dd($unallocated);
    }
}
