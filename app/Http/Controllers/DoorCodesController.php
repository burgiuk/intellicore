<?php

namespace App\Http\Controllers;

use App\Models\DoorCodes;
use Illuminate\Http\Request;
use Nette\Utils\Random;

class DoorCodesController extends Controller
{
    /**
     * @param string $name
     * @param int $length
     * @return void
     */
    public static function createDoorCodeFromName(string $name,int $length = 6){
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
     * @return void
     */
    public static function assignDoorCode(string $name, string $code){
        dd((new DoorCodesController)->checkSequenceLength($code));
    }

    /**
     * @param $code
     * @return bool
     */
    private function checkRestrictions($code){
        // Code cannot be a palindrome
        if($code === strrev($code)){
            return false;
        }


        $characters = [];
        foreach ($code as $character) {
            $character = $character->toString();
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
}
