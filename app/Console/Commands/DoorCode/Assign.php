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

        $doorcodes = new DoorCodesController();
        $validator = $doorcodes->validateInputs($name,$code);

        if($validator->fails()){
            $this->warn('The code has not been assigned for the following reasons:');

            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return false;
        }

        $assigned = $doorcodes->assignDoorCode($validator->validated()['name'],$validator->validated()['code']);
        if(!$assigned){
            $this->error('Unable to assign that code. Please try another code.');
        }

        $this->info('The code has been successfully assigned to '.$validator->validated()['name']);

        return true;
    }
}
