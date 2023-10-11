<?php

namespace App\Http\Controllers;

use App\Models\DoorCodes;
use App\Models\Unallocated;
use Nette\Utils\Random;
use Illuminate\Support\Facades\Validator;

class DoorCodesController extends Controller
{
    /**
     * @param string $name
     * @param int $length
     * @return void
     */
    public function createDoorCodeFromName(string $name,int $length = 6){
        $code = Random::generate($length,'0-9');

        $doorcode = new DoorCodes();

        $doorcode->name = $name;

        // Check restrictions

        $doorcode->code = $code;

        $doorcode->save();

        dd($doorcode);
    }

    /**
     * @param string $name
     * @param string $code
     * @return bool
     */
    public function assignDoorCode(string $name, string $code): bool
    {
        // Checks if the code is already used or if the person already has a code
        $doorcode = DoorCodes::where('code',$code)->orWhere('name',$name)->get();

        if($doorcode->count() > 0){
            return false;
        }

        // Checks the code against the business rules
        if(!$this->checkRestrictions($code)){
            return false;
        }

        $doorcode = new DoorCodes();

        $doorcode->name = $name;
        $doorcode->code = $code;
        $doorcode->save();

        $total = Unallocated::all()->first();
        if($total){
            $total->TotalAvailable--;
            $total->save();
        }else{
            $this->calculateUnallocated();
        }

        return true;
    }

    /**
     * This method calculates total number of available codes
     * @return int
     */
    public function calculateUnallocated():int{
        // Get previous result from the DB
        $total = Unallocated::all()->first();

        if($total){
            return $total->TotalAvailable;
        }

        // Maximum theoretical
        $allowedCharacters = range(0,9);
        $length = 6;
        $max =  pow(count($allowedCharacters),$length);

        // Gets all currently in use codes
        $doorcodes=DoorCodes::all();
        $totalused = $doorcodes->count();

        // Calculates total
        $total = $max - $totalused;

        $allcodes = $this->generateAllPossibleCodes();

        // Loops through generated range
        for ($i = 0; $i < $max; $i++) {
            // Checks if a code has already been taken away
            if($doorcodes->contains('code','==',$allcodes[$i])){
                continue;
            }

            // Check restrictions
            if(!$this->checkRestrictions($allcodes[$i])){
                $total--;
            }
        }

        // Save total to speed up calculations
        $unallocated = new Unallocated();
        $unallocated->TotalAvailable = $total;
        $unallocated->save();

        return $total;
    }

    /**
     * This method generates all possible codes in a given character space.
     * Inspired by https://stackoverflow.com/a/19067696/2505109
     * @param int $length
     * @return array|string[]
     */
    private function generateAllPossibleCodes(int $length = 6): array
    {
        // creates a range
        $availableCharacters = range(0,9);

        $current_set = array('');

        for ($i = 0; $i < $length; $i++) {
            $tmp_set = array();
            foreach ($current_set as $curr_elem) {
                foreach ($availableCharacters as $new_elem) {
                    $tmp_set[] = $curr_elem . $new_elem;
                }
            }
            $current_set = $tmp_set;
        }

        return $current_set;
    }

    /**
     * @param string $code
     * @return bool
     */
    private function checkRestrictions(string $code):bool{
        // Code cannot be a palindrome
        if($code === strrev($code)){
            return false;
        }

        $characters = [];
        foreach (str_split($code) as $character) {
            if(!isset($characters[$character])){
                $characters[$character] = 1;
                continue;
            }
            $characters[$character]++;
        }

        // Code must have 3 unique characters
        if(count($characters) < 3){
            return false;
        }

        // Character cannot be repeated more than 3 times
        rsort($characters);
        $firstkey = array_key_first($characters);
        if($characters[$firstkey] > 3){
            return false;
        }

        // Character cannot be in a sequence of more than 3
        if(!$this->checkSequenceLength($code)){
            return false;
        }

        return true;
    }

    /**
     * This method checks the business rule:
     * Character cannot be in a sequence of more than 3
     * @param string $code
     * @return bool
     */
    private function checkSequenceLength(string $code):bool{
        // Sets up the previous character and sequence length
        $prevChar = '';
        $sequence = 0;

        // Loops through the characters
        foreach (str_split($code) as $character) {
            // Checks if we are at the start of the door code
            if(empty($prevChar)){
                // Sets the current character to the previous character
                $prevChar = $character;
                // Sets the sequence length to 1 as we are at the first character
                $sequence = 1;
                continue;
            }

            // Calculates the next possible characters in sequence
            $testUp = $prevChar + 1;
            $testDown = $prevChar - 1;
            // Sets the current character to the previous character
            $prevChar = $character;

            // Checks if the current character matches the possible characters
            if($character == $testDown || $character == $testUp){
                // Increments the sequence if above is true
                $sequence++;
                continue;
            }
            // Resets the sequence as nothing has matched
            $sequence = 1;
        }

        // Checks if the sequence is greater than 3
        if($sequence > 3){
            return false;
        }

        // Door code is valid
        return true;
    }

    public function validateInputs($name,$code){
        $validator = Validator::make([
            'name' => $name,
            'code' => $code
        ], [
            'name' => 'required',
            'code' => 'required|numeric|max_digits:6|min_digits:6',
        ]);

        return $validator;
    }
}
