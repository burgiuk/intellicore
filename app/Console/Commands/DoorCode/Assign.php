<?php

namespace App\Console\Commands\DoorCode;

use App\Http\Controllers\DoorCodesController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

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
        // Ask for code
        $code = $this->ask('Enter the code you want to assign');

        // Ask for name
        $name = $this->ask('Enter the name of the person you want to assign '.$code.' to');


        $validator = Validator::make([
            'name' => $name,
            'code' => $code
        ], [
            'name' => 'required',
            'code' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $this->warn('Door code not assigned. See error messages below:');

            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        $doorcodes = new DoorCodesController();
        $assigned = $doorcodes->assignDoorCode($name,$code);
        if(!$assigned){
            // Failure message here
        }

        // Success message here
    }
}
