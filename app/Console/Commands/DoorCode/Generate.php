<?php

namespace App\Console\Commands\DoorCode;

use App\Http\Controllers\DoorCodesController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class Generate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'doorcode:generate';

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
        // Ask for name
        $name = $this->ask('Enter the name of the person you want to generate a code for:');

        // Validates inputs
        $validator = Validator::make([
            'name' => $name,
        ], [
            'name' => 'required',
        ]);

        if($validator->fails()){
            $this->warn('A code has not been assigned for the following reasons:');

            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return false;
        }

        $doorcodes = new DoorCodesController();
        $code = $doorcodes->createDoorCodeFromName($name);
        $this->info('The code "'.$code. '" has been assigned to '.$name);

        return true;
    }
}
