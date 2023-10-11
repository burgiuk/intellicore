<?php

namespace App\Console\Commands\DoorCode;

use App\Http\Controllers\DoorCodesController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class Reset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'doorcode:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command allows a user to reset a code or name';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $resetType = $this->choice(
            'What would you like to reset?',
            ['code', 'name']
        );

        switch ($resetType){
            case 'name':
                $question = 'Enter the name of the person you want to reset';
                $rules = ['input' => 'required'];
                break;
            case 'code':
            default:
                $question = 'Enter the code you want to reset';
                $rules = ['input' => 'required|numeric|max_digits:6|min_digits:6'];
                break;
        }

        $input = $this->ask($question);

        // Validates inputs
        $validator = Validator::make([
            'input' => $input,
        ], $rules);

        if($validator->fails()){
            $this->warn('The '.$resetType.' has not been reset for the following reasons:');

            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return false;
        }

        $reset = new DoorCodesController();
        $reset->removeDoorCode($resetType,$input);

        $this->info($input. ' has been reset');

        return true;
    }
}
